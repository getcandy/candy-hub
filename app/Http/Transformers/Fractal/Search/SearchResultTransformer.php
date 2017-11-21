<?php

namespace GetCandy\Http\Transformers\Fractal\Search;

use GetCandy\Api\Routes\Models\Route;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use Illuminate\Database\Eloquent\Model;
use GetCandy\Http\Transformers\Fractal\Products\ProductTransformer;
use GetCandy\Http\Transformers\Fractal\Categories\CategoryTransformer;

class SearchResultTransformer extends BaseTransformer
{
    protected $result = null;

    protected $types = [
        'product' => ProductTransformer::class
    ];

    public function transform($result)
    {
        $this->result = $result;

        return [
            'results' => $this->getResults(),
            'aggregates' => $this->getAggregates()
        ];
    }

    /**
     * Gets the search results
     *
     * @return void
     */
    protected function getResults()
    {
        $types = [];

        if (count($this->result)) {
            foreach ($this->result as $r) {
                $types[$r->getType()][] = $r->getSource()['id'];
            }
        }

        $results = [];

        foreach ($types as $type => $ids) {
            $factory = str_plural($type);

            $models = app('api')->{$factory}()->getSearchedIds($ids);

            $handle = str_singular($type);

            $transformer = new $this->types[$handle];

            $results[$type] = app()->fractal->createData(
                $this->collection($models, new $this->types[$handle])
            )->toArray();
        }
        return $results;
    }

    protected function getAggregates()
    {
        if (!$this->result->hasAggregations()) {
            return [];
        }

        $aggs = $this->result->getAggregations();

        $results = [];

        $selected = [];
        $all = [];

        foreach ($aggs as $handle => $agg) {
            if ($handle == 'categories_after') {
                foreach ($agg['categories_after_filter']['categories_post_inner']['buckets'] as $bucket) {
                    $selected[] = $bucket['key'];
                }
            } if ($handle == 'categories_before') {
                foreach ($agg['categories_before_inner']['buckets'] as $bucket) {
                    $all[] = $bucket['key'];
                }
            }
        }

        $selected = collect($selected);

        $models = app('api')->categories()->getSearchedIds($all);

        foreach ($models as $category) {
            $category->aggregate_selected = $selected->contains($category->encodedId());
        }

        $results['categories'] = app()->fractal->createData(
            $this->collection($models, new CategoryTransformer)
        )->toArray();

        return $results;
    }
}
