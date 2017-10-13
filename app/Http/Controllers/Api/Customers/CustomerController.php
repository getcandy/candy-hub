<?php

namespace GetCandy\Http\Controllers\Api\Customers;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Transformers\Fractal\Customers\CustomerGroupTransformer;
use Illuminate\Http\Request;
use GetCandy\Http\Requests\Api\Customers\CreateRequest;
use GetCandy\Http\Transformers\Fractal\Users\UserTransformer;

class CustomerController extends BaseController
{
    public function store(CreateRequest $request)
    {
        $customer = app('api')->customers()->register($request->all());
        return $this->respondWithItem($customer, new UserTransformer);
    }
}
