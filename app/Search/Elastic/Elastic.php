<?php

namespace GetCandy\Search\Elastic;

use Elastica\Client;
use Elastica\Document;
use Elastica\Status;
use Elastica\Type\Mapping;
use GetCandy\Api\Products\Models\Product;
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
     * @var array
     */
    protected $indexers = [
        Product::class => ProductIndexer::class,
    ];

    public function __construct(Client $client)
    {
        $this->client = $client;
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

    public function reset()
    {
        if ($this->hasIndex('dev_test_en')) {
            $this->client()->getIndex('dev_test_en')->delete();
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
            // Requested index does not exist
            $index->create();
            // $index->setSettings();
            // ...and create it's alias name
            //$index->addAlias($this->indexName);
            // ...and update the mappings
            $this->updateMappings($index);
        }

        return $index;
    }

    public function with($searchterm)
    {
        return $this->search($searchterm);
    }

    /**
     * Searches the index
     * @param  string $keywords
     * @return array
     */
    public function search($keywords)
    {
        if (!$this->indexer) {
            abort(400, 'You need to set an indexer first');
        }

        $search = new \Elastica\Search($this->client);


        $search
            ->addIndex(config('search.index'))
            ->addType($this->indexer->type);


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

        $query = new \Elastica\Query();

        $query
            ->setQuery($disMaxQuery)
            ->setHighlight(array(
                'pre_tags' => array('<em class="highlight">'),
                'post_tags' => array('</em>'),
                'fields' => array(
                    'name' => array(
                        'number_of_fragments' => 0,
                    ),
                    'description' => array(
                        'number_of_fragments' => 0,
                    ),
                ),
            ));

        $search->setQuery($query);

        $results = $search->search();
        return $results;
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
