<?php

namespace GetCandy\Http\Controllers\Api\Categories;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Requests\Api\Categories\Routes\CreateRequest;
use Illuminate\Http\Request;

class CategoryRouteController extends BaseController
{
    /**
     * @param                                                       $product
     * @param \GetCandy\Http\Requests\Api\Products\CreateUrlRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store($category, CreateRequest $request)
    {
        $result = app('api')->categories()->createUrl($category, $request->all());
        return $this->respondWithNoContent();
    }
}
