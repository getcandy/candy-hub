<?php

namespace GetCandy\Http\Controllers\Api\Routes;

use GetCandy\Exceptions\MinimumRecordRequiredException;
use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Transformers\Fractal\Routes\RouteTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RouteController extends BaseController
{
    public function index()
    {
        $pages = app('api')->routes()->getPaginatedData();
        return $this->respondWithCollection($pages, new RouteTransformer);
    }
    /**
     * Handles the request to show a route based on it's hashed ID
     * @param  String $slug
     * @return Json
     */
    public function show($slug)
    {
        try {
            $route = app('api')->routes()->getBySlug($slug);
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($route, new RouteTransformer);
    }

    public function destroy($id)
    {
        try {
            $result = app('api')->routes()->delete($id);
        } catch (MinimumRecordRequiredException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
    }
}
