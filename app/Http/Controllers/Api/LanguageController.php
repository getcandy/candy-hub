<?php

namespace GetCandy\Http\Controllers\Api;

use GetCandy\Api\Exceptions\MinimumRecordRequiredException;
use GetCandy\Http\Requests\Api\Languages\CreateRequest;
use GetCandy\Http\Requests\Api\Languages\DeleteRequest;
use GetCandy\Http\Requests\Api\Languages\UpdateRequest;
use GetCandy\Http\Transformers\Fractal\LanguageTransformer;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LanguageController extends BaseController
{
    /**
     * Returns a listing of channels
     * @return Json
     */
    public function index(Request $request)
    {
        $paginator = app('api')->languages()->getPaginatedData($request->per_page);
        return $this->respondWithCollection($paginator, new LanguageTransformer);
    }

    /**
     * Returns a single Language
     * @return Json
     */
    public function show($id)
    {
        try {
            $language = app('api')->languages()->getByHashedId($id);
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($language, new LanguageTransformer);
    }

    /**
     * Handles the request to create a new language
     * @param  CreateRequest $request
     * @return Json
     */
    public function store(CreateRequest $request)
    {
        $result = app('api')->languages()->create($request->all());
        return $this->respondWithItem($result, new LanguageTransformer);
    }

    /**
     * Handles the request to update  a language
     * @param  String        $id
     * @param  UpdateRequest $request
     * @return Json
     */
    public function update($id, UpdateRequest $request)
    {
        try {
            $result = app('api')->languages()->update($id, $request->all());
        } catch (MinimumRecordRequiredException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($result, new LanguageTransformer);
    }

    /**
     * Handles the request to delete a language
     * @param  String        $id
     * @param  DeleteRequest $request
     * @return Json
     */
    public function destroy($id, DeleteRequest $request)
    {
        try {
            $result = app('api')->languages()->delete($id);
        } catch (MinimumRecordRequiredException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithNoContent();
    }
}
