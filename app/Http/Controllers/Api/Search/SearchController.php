<?php

namespace GetCandy\Http\Controllers\Api\Search;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Transformers\Fractal\Products\ProductTransformer;
use Illuminate\Http\Request;
use GetCandy\Search\SearchContract;
use GetCandy\Api\Products\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use GetCandy\Http\Requests\Api\Search\SearchRequest;

class SearchController extends BaseController
{
    protected $types = [
        'product' => Product::class
    ];

    /**
     * Performs a search against a type
     *
     * @param Request $request
     * @param SearchContract $client
     * 
     * @return Array
     */
    public function search(SearchRequest $request, SearchContract $client)
    {
        if (empty($this->types[$request->type])) {
            return $this->errorWrongArgs('Invalid type');
        }
        $results = $client
            ->language(app()->getLocale())
            ->against($this->types[$request->type])
            ->search($request->keywords, $request->filters);
        dd($results);
    }

    public function products(Request $request, SearchContract $client)
    {
        $results = $client->against(Product::class)->search($request->keywords);

        $ids = [];
        if (count($results)) {
            foreach ($results as $r) {
                $ids[] = $r->getSource()['id'];
            }
        }

        $products = app('api')->products()->getSearchedIds($ids);

        return $this->respondWithCollection($products, new ProductTransformer);
    }
}
