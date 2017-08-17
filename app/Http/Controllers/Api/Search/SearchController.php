<?php

namespace GetCandy\Http\Controllers\Api\Search;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Transformers\Fractal\Products\ProductTransformer;
use Illuminate\Http\Request;

class SearchController extends BaseController
{
    public function internal(Request $request)
    {
        $result = app('api')->search()->internalSearch($request->all());
        return $this->respondWithCollection($result, new ProductTransformer);
    }
}
