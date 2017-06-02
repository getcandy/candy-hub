<?php

namespace GetCandy\Http\Controllers\Api;

use GetCandy\Api\Exceptions\MinimumRecordRequiredException;
use GetCandy\Http\Transformers\Fractal\PageTransformer;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PageController extends BaseController
{
    /**
     * Handles the request to show a currency based on it's hashed ID
     * @param  String $id
     * @return Json
     */
    public function show($channel, $lang, $slug = null)
    {
        try {
            $currency = app('api')->pages()->findPage($channel, $lang, $slug);
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($currency, new PageTransformer);
    }
}
