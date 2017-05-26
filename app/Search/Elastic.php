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

    /**
     * Sets the index to search on
     * @param  string $index
     * @return this
     */
    public function index($index)
    {
        $this->index = $index;
        return $this;
    }

    /**
     * Searches the index
     * @param  string $keywords
     * @return array
     */
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
