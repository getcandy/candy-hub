<?php

namespace GetCandy\Http\Controllers\Api;

use GetCandy\Exceptions\MinimumRecordRequiredException;
use GetCandy\Http\Requests\Api\Currencies\CreateRequest;
use GetCandy\Http\Requests\Api\Currencies\DeleteRequest;
use GetCandy\Http\Requests\Api\Currencies\UpdateRequest;
use GetCandy\Http\Transformers\Fractal\CurrencyTransformer;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CurrencyController extends BaseController
{
    /**
     * Returns a listing of currencies
     * @return Json
     */
    public function index(Request $request)
    {
        $paginator = app('api')->currencies()->dataGetPaginatedResults($request->per_page);
        return $this->respondWithCollection($paginator, new CurrencyTransformer);
    }

    /**
     * Handles the request to show a currency based on it's hashed ID
     * @param  String $id
     * @return Json
     */
    public function show($id)
    {
        try {
            $currency = app('api')->currencies()->dataGetByHashedId($id);
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($currency, new CurrencyTransformer);
    }

    /**
     * Handles the request to create a new channel
     * @param  CreateRequest $request
     * @return Json
     */
    public function store(CreateRequest $request)
    {
        $result = app('api')->currencies()->create($request->all());
        return $this->respondWithItem($result, new CurrencyTransformer);
    }

    public function update($id, UpdateRequest $request)
    {
        try {
            $result = app('api')->currencies()->update($id, $request->all());
        } catch (MinimumRecordRequiredException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($result, new CurrencyTransformer);
    }

    /**
     * Handles the request to delete a currency
     * @param  String        $id
     * @param  DeleteRequest $request
     * @return Json
     */
    public function destroy($id, DeleteRequest $request)
    {
        try {
            $result = app('api')->currencies()->deleteByHashedId($id);
        } catch (MinimumRecordRequiredException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithNoContent();
    }
}
