<?php

namespace GetCandy\Http\Controllers\Api;

use GetCandy\Api\Attributes\AttributeManager;
use GetCandy\Http\Requests\Api\Attributes\CreateRequest;
use GetCandy\Http\Requests\Api\Attributes\DeleteRequest;
use GetCandy\Http\Requests\Api\Attributes\ReorderRequest;
use GetCandy\Http\Requests\Api\Attributes\UpdateRequest;
use GetCandy\Http\Transformers\AttributeTransformer;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AttributeController extends BaseController
{
    /**
     * @var AttributeManager
     */
    protected $attributeManager;

    public function __construct(
        AttributeManager $attributeManager
    ) {
        $this->attributeManager = $attributeManager;
    }

    /**
     * Returns a listing of channels
     * @return Json
     */
    public function index(Request $request)
    {
        $attributes = $this->attributeManager->attributes()->dataGetPaginatedResults($request->per_page);
        return $this->respondWithCollection($attributes, new AttributeTransformer);
    }

    /**
     * Handles the request to show a channel based on it's hashed ID
     * @param  String $id
     * @return Json
     */
    public function show($id)
    {
        try {
            $attribute = $this->attributeManager->attributes()->dataGetByHashedId($id);
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($attribute, new AttributeTransformer);
    }

    /**
     * Handles the request to create a new channel
     * @param  CreateRequest $request
     * @return Json
     */
    public function store(CreateRequest $request)
    {
        $result = $this->attributeManager->attributes()->create($request->all());
        return $this->respondWithItem($result, new AttributeTransformer);
    }

    public function reorder(ReorderRequest $request)
    {
        try {
            $result = $this->attributeManager->attributes()->reorder($request->all());
        } catch (HttpException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (DuplicateValueException $e) {
            return $this->errorWrongArgs($e->getMessage());
        }
        return $this->respondWithNoContent();
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
            $result = $this->attributeManager->attributes()->update($id, $request->all());
        } catch (MinimumRecordRequiredException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($result, new AttributeTransformer);
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
            $result = $this->attributeManager->attributes()->deleteByHashedId($id);
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithNoContent();
    }
}
