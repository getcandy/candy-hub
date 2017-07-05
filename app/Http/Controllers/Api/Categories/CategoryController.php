<?php

namespace GetCandy\Http\Controllers\Api\Categories;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Transformers\Fractal\Categories\CategoryTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends BaseController
{
    /**
     * Returns a listing of channels
     * @return Json
     */
    public function index(Request $request)
    {
        $results = app('api')->categories()->getNestedList();
        return $this->respondWithCollection($results, new CategoryTransformer);
    }
}
