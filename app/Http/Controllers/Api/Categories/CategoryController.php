<?php

namespace GetCandy\Http\Controllers\Api\Categories;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Transformers\Fractal\Categories\CategoryTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use GetCandy\Http\Requests\Api\Categories\StoreRequest;
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

    /**
     * Handles the request to update a product
     * @param  String        $id
     * @param  UpdateRequest $request
     * @return array|\Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        try {
            $results = app('api')->categories()->update($request->all());
        } catch (MinimumRecordRequiredException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        } catch (HttpException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (InvalidLanguageException $e) {
            return $this->errorUnprocessable($e->getMessage());
        }
        return $this->respondWithCollection($results, new CategoryTransformer);
    }
}
