<?php

namespace GetCandy\Http\Controllers\Api\Discounts;

use GetCandy\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;

class DiscountController extends BaseController
{

    public function index()
    {

    }

    public function store(Request $request)
    {
        app('api')->discounts()->create($request->all());
    }
}
