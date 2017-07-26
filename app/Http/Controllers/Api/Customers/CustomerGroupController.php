<?php

namespace GetCandy\Http\Controllers\Api\Customers;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Transformers\Fractal\Customers\CustomerGroupTransformer;
use Illuminate\Http\Request;

class CustomerGroupController extends BaseController
{
    public function index(Request $request)
    {
        $groups = app('api')->customerGroups()->getPaginatedData($request->keywords, $request->per_page);
        return $this->respondWithCollection($groups, new CustomerGroupTransformer());
    }
}
