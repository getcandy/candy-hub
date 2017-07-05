<?php

namespace GetCandy\Http\Controllers\Api\Collections;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Requests\Api\Collections\CreateRequest;
use GetCandy\Http\Requests\Api\Collections\DeleteRequest;
use GetCandy\Http\Requests\Api\Collections\UpdateRequest;
use GetCandy\Http\Transformers\Fractal\Collections\CollectionTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CollectionController extends BaseController
{
    /**
     * Returns a listing of channels
     * @return Json
     */
    public function index(Request $request)
    {
        $paginator = app('api')->collections()->getPaginatedData($request->per_page);
        return $this->respondWithCollection($paginator, new CollectionTransformer);
    }

    /**
     * Handles the request to show a channel based on it's hashed ID
     * @param  String $id
     * @return Json
     */
    public function show($id)
    {
        try {
            $channel = app('api')->channels()->getByHashedId($id);
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($channel, new ChannelTransformer);
    }

    /**
     * Handles the request to create a new channel
     * @param  CreateRequest $request
     * @return Json
     */
    public function store(CreateRequest $request)
    {
        $result = app('api')->channels()->create($request->all());
        return $this->respondWithItem($result, new ChannelTransformer);
    }

    /**
     * Handles the request to update  a channel
     * @param  String        $id
     * @param  UpdateRequest $request
     * @return Json
     */
    public function update($id, UpdateRequest $request)
    {
        try {
            $result = app('api')->channels()->update($id, $request->all());
        } catch (MinimumRecordRequiredException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($result, new ChannelTransformer);
    }

    /**
     * Handles the request to delete a channel
     * @param  String        $id
     * @param  DeleteRequest $request
     * @return Json
     */
    public function destroy($id, DeleteRequest $request)
    {

        try {
            $result = app('api')->channels()->delete($id);
        } catch (MinimumRecordRequiredException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithNoContent();
    }
}
