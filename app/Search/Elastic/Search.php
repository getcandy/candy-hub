<?php

namespace GetCandy\Search\Elastic;

use Elastica\Aggregation\Filter as FilterAggregation;
use Elastica\Aggregation\Nested as NestedAggregation;
use Elastica\Aggregation\Terms;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elastica\Query\Nested as NestedQuery;
use Elastica\Query\Term;
use Elastica\Suggest;
use Elastica\Suggest\CandidateGenerator\DirectGenerator;
use Elastica\Suggest\Phrase;
use GetCandy\Search\ClientContract;

class Search extends AbstractProvider implements ClientContract
{
    protected $categories = [];

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
            ->addIndex(config('search.index_prefix') . '_' . $this->lang)
            ->addType($this->indexer->type);

        $query = new Query();
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

        $generator = new DirectGenerator('name');
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
            $postBool->addMust($term);
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
        // $postFilter = new NestedQuery();
        // $postFilter->setPath('departments');

        $query = new BoolQuery;

        $filter = new BoolQuery;

        foreach ($categories as $value) {
            $cat = new NestedQuery();
            $cat->setPath('departments');

            $term = new Term;
            $term->setTerm('departments.id', $value);

            $cat->setQuery($term);

            $filter->addMust($cat);
            $this->categories[] = $value;
        }

        $query->addFilter($filter);

        // // $postFilterQuery->setMinimumShouldMatch(1);

        // $postFilter->setQuery($postFilterQuery);

        return $query;
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
}