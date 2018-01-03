<?php

namespace GetCandy\Search\Elastic;

use Elastica\Aggregation\Filter as FilterAggregation;
use Elastica\Aggregation\Nested as NestedAggregation;
use Elastica\Aggregation\Terms;
use Elastica\Client;
use Elastica\Document;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elastica\Query\Nested as NestedQuery;
use Elastica\Query\Term;
use Elastica\Status;
use Elastica\Suggest;
use Elastica\Suggest\Phrase;
use Elastica\Type\Mapping;
use GetCandy\Api\Categories\Models\Category;
use GetCandy\Api\Products\Models\Product;
use GetCandy\Search\Elastic\Indexers\CategoryIndexer;
use GetCandy\Search\Elastic\Indexers\ProductIndexer;
use GetCandy\Search\SearchContract;
use Illuminate\Database\Eloquent\Model;

class Elastic implements SearchContract
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var mixed
     */
    protected $indexer;

    /**
     * @var string
     */
    protected $lang = 'en';

    /**
     * @var array
     */
    protected $categories = [];

    /**
     * @var array
     */
    protected $indexers = [
        Product::class => ProductIndexer::class,
        Category::class => CategoryIndexer::class
    ];

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function language($lang = 'en')
    {
        $this->lang = $lang;
        return $this;
    }

    public function against($types)
    {
        $this->indexer = $this->getIndexer($types);
        return $this;
    }
    /**
     * Checks whether an indexer exists
     * @param  mixed  $model
     * @return boolean
     */
    public function hasIndexer($model)
    {
        if (is_object($model)) {
            $model = get_class($model);
        }
        return isset($this->indexers[$model]);
    }

    /**
     * Adds a model to the index
     * @param  Model  $model
     * @return boolean
     */
    public function indexObject(Model $model)
    {
        // Get the indexer.
        // $indexer = $this->getIndexer($model);
        // $index =
        // $elasticaType = $index->getType($indexer->type);
        // $response = $elasticaType->addDocument($indexer->getIndexDocument($model));

        $this->against($model);

        $indexables = $this->indexer->getIndexDocument($model);

        foreach ($indexables as $indexable) {
            $index = $this->getIndex($indexable->getIndex());

            $elasticaType = $index->getType($this->indexer->type);

            $document = new Document(
                $indexable->getId(),
                $indexable->getData()
            );

            $response = $elasticaType->addDocument($document);
        }
        return true;
    }

    public function reset($index)
    {
        if ($this->hasIndex($index)) {
            $this->client()->getIndex($index)->delete();
        }
    }

    /**
     * Updates the mappings for the model
     * @param  Elastica\Index $index
     * @return void
     */
    public function updateMappings($index)
    {
        $elasticaType = $index->getType($this->indexer->type);

        $mapping = new Mapping();
        $mapping->setType($elasticaType);

        $mapping->setProperties($this->indexer->mapping());
        $mapping->send();
    }

    /**
     * Create an index based on the model
     * @return void
     */
    public function createIndex()
    {
        $index = $this->client()->getIndex('getcandy');
        $index->create();
    }

    /**
     * Gets the client for the model
     * @return Elastica\Client
     */
    public function client()
    {
        if (! $this->client) {
            return new Client();
        }
        return $this->client;
    }

    public function hasIndex($name)
    {
        $elasticaStatus = new Status($this->client());
        return $elasticaStatus->indexExists($name) or $elasticaStatus->aliasExists($name);
    }

    /**
     * Returns the index for the model
     * @return Elastica\Index
     */
    public function getIndex($name = null)
    {
        $index = $this->client()->getIndex($name);

        if (!$this->hasIndex($name)) {
            $index->create([
                'analysis' => [
                    'analyzer' => [
                        'trigram' => [
                            'type' => 'custom',
                            'tokenizer' => 'standard',
                            'filter' => ['standard', 'shingle']
                        ]
                    ],
                    'filter' => [
                        'shingle' => [
                            'type' => 'shingle',
                            'min_shingle_size' => 2,
                            'max_shingle_size' => 3
                        ]
                    ]
                ]
            ]);
            $index->addAlias($name . '_alias');
            // ...and update the mappings
            $this->updateMappings($index);
        }

        return $index;
    }

    public function with($searchterm)
    {
        return $this->search($searchterm);
    }

    protected function getSearchIndex($indexer)
    {
        return config('search.index_prefix') . $this->lang;
    }

    /**
     * Searches the index
     * 
     * @param  string $keywords
     * 
     * @return array
     */
    public function search($keywords, $filters = [], $page = 1, $perPage = 25)
    {
        if (!$this->indexer) {
            abort(400, 'You need to set an indexer first');
        }

        $search = new \Elastica\Search($this->client);
        $search
            ->addIndex(config('search.index_prefix') . '_' .  $this->lang)
            ->addType($this->indexer->type);

        $query = new \Elastica\Query();
        $query->setParam('size', $perPage);
        $query->setParam('from', ($page - 1) * $perPage);
        
        if ($keywords) {
            $boolQuery = new BoolQuery;
            $disMaxQuery = $this->generateDisMax($keywords);
            $boolQuery->addMust($disMaxQuery);
            $query->setQuery($boolQuery);
        }

        $query->setHighlight(
            $this->getHighlight()
        );

        $query->addAggregation(
            $this->getCategoryPreAgg()
        );

        if (!empty($filters['categories'])) {
            $categories = $filters['categories']['values'];
            $filter = $this->getCategoryFilter($categories);

            $query->setQuery($filter);
            $query->setPostFilter(
                $filter
            );
        }

        $query->addAggregation(
            $this->getCategoryPostAgg()
        );

        // dd($query);

        if ($keywords) {
            $query->setSuggest(
                $this->getSuggest($keywords)
            );
        }
    
        $search->setQuery($query);

        $results = $search->search();
        return $results;
    }

    /**
     * Get the suggester
     *
     * @return Suggest
     */
    protected function getSuggest($keywords)
    {
        // Did you mean...
        $phrase = new Phrase(
            'name',
            'name'
        );
        $phrase->setGramSize(3);
        $phrase->setSize(1);
        $phrase->setText($keywords);

        $generator = new \Elastica\Suggest\CandidateGenerator\DirectGenerator('name');
        $generator->setSuggestMode('always');
        $generator->setField('name');
        $phrase->addCandidateGenerator($generator);

        $phrase->setHighlight('<strong>', '</strong>');
        $suggest = new Suggest;
        $suggest->addSuggestion($phrase);

        return $suggest;
    }

    /**
     * Gets the category post aggregation
     *
     * @return NestedAggregation
     */
    protected function getCategoryPostAgg()
    {
        $nestedAggPost = new NestedAggregation(
            'categories_after',
            'departments'
        );

        $agg = new FilterAggregation('categories_after_filter');

        // Add boolean
        $postBool = new BoolQuery();
        
        foreach ($this->categories as $category) {
            $term = new Term;
            $term->setTerm('departments.id', $category);
            $postBool->addShould($term);
        }

        // Need to set another agg on categories_remaining
        $childAgg = new \Elastica\Aggregation\Terms('categories_post_inner');
        $childAgg->setField('departments.id');

        // Do the terms in the categories loop...
        $agg->setFilter($postBool);
        $agg->addAggregation($childAgg);

        $nestedAggPost->addAggregation($agg);

        return $nestedAggPost;
    }

    /**
     * Returns the category before aggregation
     *
     * @return NestedAggregation
     */
    protected function getCategoryPreAgg()
    {
        // Get our category aggregations
        $nestedAggBefore = new NestedAggregation(
            'categories_before',
            'departments'
        );

        $childAgg = new \Elastica\Aggregation\Terms('categories_before_inner');
        $childAgg->setField('departments.id');

        $nestedAggBefore->addAggregation($childAgg);

        return $nestedAggBefore;
    }

    /**
     * Gets the highlight for the search query
     *
     * @return array
     */
    protected function getHighlight()
    {
        return [
            'pre_tags' => ['<em class="highlight">'],
            'post_tags' => ['</em>'],
            'fields' => [
                'name' => [
                    'number_of_fragments' => 0,
                ],
                'description' => [
                    'number_of_fragments' => 0,
                ],
            ],
        ];
    }

    /**
     * Gets the category post filter
     *
     * @param array $categories
     * 
     * @return void
     */
    protected function getCategoryFilter($categories = [])
    {
        $postFilter = new NestedQuery();
        $postFilter->setPath('departments');

        $postFilterQuery = new BoolQuery;

        foreach ($categories as $value) {
            $term = new Term;
            $term->setTerm('departments.id', $value);
            $postFilterQuery->addShould($term);
            $this->categories[] = $value;
        }

        $postFilter->setQuery($postFilterQuery);

        return $postFilter;
    }

    protected function generateAggregates()
    {

    }

    /**
     * Generates the DisMax query
     *
     * @param string $keywords
     * 
     * @return void
     */
    protected function generateDisMax($keywords)
    {
        $disMaxQuery = new \Elastica\Query\DisMax();
        $disMaxQuery->setBoost(1.5);
        $disMaxQuery->setTieBreaker(1);

        $multiMatchQuery = new \Elastica\Query\MultiMatch();
        $multiMatchQuery->setType('phrase');
        $multiMatchQuery->setQuery($keywords);
        $multiMatchQuery->setFields($this->indexer->rankings());

        $disMaxQuery->addQuery($multiMatchQuery);

        $multiMatchQuery = new \Elastica\Query\MultiMatch();
        $multiMatchQuery->setType('best_fields');
        $multiMatchQuery->setQuery($keywords);

        $multiMatchQuery->setFields($this->indexer->rankings());

        $disMaxQuery->addQuery($multiMatchQuery);

        return $disMaxQuery;
    }

    /**
     * Gets the indexer for a model
     * @param  mixed $model
     * @return mixed
     */
    protected function getIndexer($model)
    {
        if (is_object($model)) {
            $model = get_class($model);
        }
        if (!$this->hasIndexer($model)) {
            abort(400, "No indexer available for {$model}");
        }
        return new $this->indexers[$model];
    }
}
