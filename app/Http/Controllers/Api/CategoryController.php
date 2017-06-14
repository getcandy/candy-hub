<?php

namespace GetCandy\Http\Controllers\Api;

use GetCandy\Http\Transformers\Fractal\CategoryTransformer;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
