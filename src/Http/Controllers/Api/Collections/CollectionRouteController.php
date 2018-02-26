<?php

namespace GetCandy\Http\Controllers\Api\Collections;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Requests\Api\Collections\Routes\CreateRequest;
use Illuminate\Http\Request;

class CollectionRouteController extends BaseController
{
    /**
     * @param                                                       $product
     * @param \GetCandy\Http\Requests\Api\Products\CreateUrlRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store($collection, CreateRequest $request)
    {
        $result = app('api')->collections()->createUrl($collection, $request->all());
        return $this->respondWithNoContent();
    }
}
