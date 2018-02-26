<?php

namespace GetCandy\Http\Controllers\Api\Products;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Requests\Api\Products\CreateUrlRequest;
use GetCandy\Http\Requests\Api\Products\UpdateUrlsRequest;
use Illuminate\Http\Request;

class ProductRouteController extends BaseController
{
    /**
     * @param                                                       $product
     * @param \GetCandy\Http\Requests\Api\Products\CreateUrlRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store($product, CreateUrlRequest $request)
    {
        $result = app('api')->products()->createUrl($product, $request->all());
        return $this->respondWithNoContent();
    }

    public function update($product, UpdateUrlsRequest $request)
    {
        $result = app('api')->products()->saveUrls($product, $request->urls);
        return $this->respondWithNoContent();
    }
}
