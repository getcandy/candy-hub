<?php

namespace GetCandy\Http\Controllers\Api\Search;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Transformers\Fractal\Products\ProductTransformer;
use Illuminate\Http\Request;
use GetCandy\Search\SearchContract;
use GetCandy\Api\Products\Models\Product;

class SearchController extends BaseController
{
    public function search(Request $request, SearchContract $client)
    {
        $results = $client->against(Product::class)->search($request->keywords);

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
