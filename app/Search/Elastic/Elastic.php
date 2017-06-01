<?php

namespace GetCandy\Search\Elastic;

use Elastica\Client;
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

    public function hasIndexer($model)
    {
        //..
    }

    public function index(Model $model)
    {
        $indexer = new $this->indexers[get_class($model)];
        dd($indexer);
    }

    public function on($model)
    {

        if (empty($this->indexers[$model])) {
            abort(400, "No indexer available for {$model}");
        }
        $this->indexer = new $this->indexers[$model];
        return $this;
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
            ->addIndex('getcandy')
            ->addType($this->indexer->type)
            ->setOption(\Elastica\Search::OPTION_TIMEOUT, '100ms')
            ->setOption(\Elastica\Search::OPTION_SEARCH_TYPE, \Elastica\Search::OPTION_SEARCH_TYPE_DFS_QUERY_THEN_FETCH);


        $multiMatchQuery = new \Elastica\Query\MultiMatch();
        $multiMatchQuery->setType('best_fields');
        $multiMatchQuery->setQuery($keywords);
        $multiMatchQuery->setTieBreaker(0.5);
        $multiMatchQuery->setFuzziness(100);

        // $searchableFields = [
        //     "sku^10", "name^5", "name.english^4", "description^3", "description.english^2", "keywords", "keywords.english", "ean", "category_name", "category_name.english", "category_breadcrumb", "category_breadcrumb.english"
        // ];

        $searchableFields = [
            "name^5", "name.english^4"
        ];

        $multiMatchQuery->setFields($searchableFields);

        $query = new \Elastica\Query();
        $query
            ->setFrom(0)
            ->setSize(1)
            ->setMinScore(0);

        $query->setQuery($multiMatchQuery);

        $search->setQuery($query);

        $query
            ->setFrom(0)
            ->setSize(100)
            // ->setMinScore()  // We'll only grab results with a score of at least 50% of the first result
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

        $results = $search->search();

        $ids = [];
        if (count($results)) {
            foreach ($results as $r) {
                $ids[] = $r->getSource()['id'];
            }
        }
        return $ids;
    }
}
