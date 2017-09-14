<?php

namespace GetCandy\Http\Controllers\Api\Categories;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Transformers\Fractal\Categories\CategoryTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use GetCandy\Http\Requests\Api\Categories\CreateRequest;
use GetCandy\Http\Requests\Api\Categories\ReorderRequest;
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
     * Create new category from basic information
     * @param $request
     * @request String name
     * @request String slug
     * @request String parent_id (Optional)
     * @return array|\Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {

        try {
            $result = app('api')->categories()->create($request->all());
        } catch (MinimumRecordRequiredException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        } catch (HttpException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (InvalidLanguageException $e) {
            return $this->errorUnprocessable($e->getMessage());
        }
        return $this->respondWithItem($result, new CategoryTransformer);
    }

    /**
     * Handles the request to reorder the categories
     * @param $request
     * @request String id
     * @request String siblings
     * @request String parent_id (Optional)
     * @return array|\Illuminate\Http\Response
     */
    public function reorder(ReorderRequest $request)
    {
        try {
            $results = app('api')->categories()->reorder($request->all());
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
