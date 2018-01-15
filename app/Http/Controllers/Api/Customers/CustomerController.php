<?php

namespace GetCandy\Http\Controllers\Api\Customers;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Transformers\Fractal\Customers\CustomerGroupTransformer;
use Illuminate\Http\Request;
use GetCandy\Http\Requests\Api\Customers\CreateRequest;
use GetCandy\Http\Transformers\Fractal\Users\UserTransformer;

class CustomerController extends BaseController
{
    /**
     * Shows all the customers
     *
     * @return array
     */
    public function index(Request $request)
    {
        $customers = app('api')->customers()->getPaginatedData(
            $request->length,
            $request->page,
            $request->keywords
        );
        return $this->respondWithCollection($customers, new UserTransformer);
    }

    public function show($id, Request $request)
    {
        $customer = app('api')->customers()->getByHashedId($id);
        return $this->respondWithItem($customer, new UserTransformer);
    }

    /**
     * Handles request to store a customer
     *
     * @param CreateRequest $request
     * 
     * @return array
     */
    public function store(CreateRequest $request)
    {
        $customer = app('api')->customers()->register($request->all());
        return $this->respondWithItem($customer, new UserTransformer);
    }
}
