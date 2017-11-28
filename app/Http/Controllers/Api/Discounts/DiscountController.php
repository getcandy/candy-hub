<?php

namespace GetCandy\Http\Controllers\Api\Discounts;

use GetCandy\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use GetCandy\Http\Requests\Api\Discounts\CreateRequest;

class DiscountController extends BaseController
{

    public function index()
    {

    }

    public function store(CreateRequest $request)
    {
        app('api')->discounts()->create($request->all());
    }
}
