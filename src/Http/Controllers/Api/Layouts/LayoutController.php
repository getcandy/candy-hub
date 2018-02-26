<?php

namespace GetCandy\Http\Controllers\Api\Layouts;

use GetCandy\Api\Exceptions\MinimumRecordRequiredException;
use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Transformers\Fractal\Layouts\LayoutTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LayoutController extends BaseController
{
    public function index()
    {
        $pages = app('api')->layouts()->getPaginatedData();
        return $this->respondWithCollection($pages, new LayoutTransformer);
    }
    /**
     * Handles the request to show a layout based on it's hashed ID
     * @param  String $id
     * @return Json
     */
    public function show($id)
    {
        try {
            $currency = app('api')->layouts()->getByEncodedId($id);
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($currency, new PageTransformer);
    }
}
