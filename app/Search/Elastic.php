<?php

namespace GetCandy\Search;

use Elastica\Client;
use Elastica\Status;
use Elastica\Type\Mapping;

class Elastic implements SearchContract
{
    protected $index;
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function index($index)
    {
        $this->index = $index;
        return $this;
    }

    public function search($keywords)
    {
        $search = new \Elastica\Search($this->client);

        $search
            ->addIndex($this->index)
            ->addType('eloquent')
            ->setOption(\Elastica\Search::OPTION_TIMEOUT, '100ms')
            ->setOption(\Elastica\Search::OPTION_SEARCH_TYPE, \Elastica\Search::OPTION_SEARCH_TYPE_DFS_QUERY_THEN_FETCH);


        $multiMatchQuery = new \Elastica\Query\MultiMatch();
        $multiMatchQuery->setType('best_fields');
        $multiMatchQuery->setQuery($keywords);
        $multiMatchQuery->setTieBreaker(0.5);
        $multiMatchQuery->setFuzziness(1);

        // $searchableFields = [
        //     "sku^10", "name^5", "name.english^4", "description^3", "description.english^2", "keywords", "keywords.english", "ean", "category_name", "category_name.english", "category_breadcrumb", "category_breadcrumb.english"
        // ];

        $searchableFields = [
            "name^10", "name.english^4"
        ];

        $multiMatchQuery->setFields($searchableFields);

        $query = new \Elastica\Query();
        $query
            ->setFrom(0)
            ->setSize(1)
            ->setMinScore(0);

        $query->setQuery($multiMatchQuery);

        $search->setQuery($query);

        $this->resultSet = $search->search();

        $maxScore = $this->resultSet->getResponse()->getData()['hits']['max_score'];

        $query
            ->setFrom(0)
            ->setSize(100)
            ->setMinScore($maxScore * 0.5)  // We'll only grab results with a score of at least 50% of the first result
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

        $this->resultSet = $search->search();

        // dump($this->resultSet);

        $count = 0;

        // echo "Query time: " . $this->resultSet->getTotalTime() . '<br>';
        // echo "Hits: " . $this->resultSet->getTotalHits();

        return $this->resultSet;
    }
}
