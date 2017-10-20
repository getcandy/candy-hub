<?php

namespace GetCandy\Http\Controllers\Api\Products;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Requests\Api\Products\Associations\CreateRequest;

class ProductAssociationController extends BaseController
{
    /**
     * Handles the request to update a products attributes
     * @param  String        $product
     * @param  UpdateAttributesRequest $request
     * @return Mixed
     */
    public function store($product, CreateRequest $request)
    {
        $result = app('api')->productAssociations()->store($product, $request->all());
        return $this->respondWithSuccess();
    }
}
