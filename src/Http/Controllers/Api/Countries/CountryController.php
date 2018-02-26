<?php

namespace GetCandy\Http\Controllers\Api\Countries;

use Illuminate\Http\Request;
use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Transformers\Fractal\Countries\CountryTransformer;
use GetCandy\Http\Transformers\Fractal\Countries\CountryGroupTransformer;

class CountryController extends BaseController
{
    /**
     * Returns a listing of channels
     * @return Json
     */
    public function index(Request $request)
    {
        $collection = app('api')->countries()->getGroupedByRegion();

        return $this->respondWithCollection($collection, new CountryGroupTransformer);
    }
}
