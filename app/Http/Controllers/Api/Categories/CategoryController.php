<?php

namespace GetCandy\Http\Controllers\Api\Categories;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Transformers\Fractal\Categories\CategoryTransformer;
use GetCandy\Http\Transformers\Fractal\Categories\CategoryFancytreeTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use GetCandy\Http\Requests\Api\Categories\CreateRequest;
use GetCandy\Http\Requests\Api\Categories\ReorderRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends BaseController
{
    /**
     * Returns a listing of categories at one level
     * @return Json
     */
    public function getNestedCategories()
    {
        $categories = app('api')->categories()->getNestedList();
        return $this->respondWithCollection($categories, new CategoryTransformer);
    }

    public function getCategories($parentID = null)
    {
        $categories = app('api')->categories()->getByParentID($parentID);
        return $this->respondWithCollection($categories, new CategoryFancytreeTransformer);
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
            $results = app('api')->categories()->create($request->all());
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
            $response = app('api')->categories()->reorder($request->all());
        } catch (MinimumRecordRequiredException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        } catch (HttpException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (InvalidLanguageException $e) {
            return $this->errorUnprocessable($e->getMessage());
        }
        if($response){
            return response()->json(['status' => 'success'],200);
        }
        return response()->json(['status' => 'error'],500);
    }
}
