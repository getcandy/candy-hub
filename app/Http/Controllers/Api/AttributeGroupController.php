<?php

namespace GetCandy\Http\Controllers\Api;

use GetCandy\Api\Attributes\AttributeManager;
use GetCandy\Exceptions\DuplicateValueException;
use GetCandy\Http\Transformers\AttributeGroupTransformer;
use GetCandy\Http\Requests\Api\AttributeGroups\CreateRequest;
use GetCandy\Http\Requests\Api\AttributeGroups\DeleteRequest;
use GetCandy\Http\Requests\Api\AttributeGroups\ReorderRequest;
use GetCandy\Http\Requests\Api\AttributeGroups\UpdateRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AttributeGroupController extends BaseController
{

    /**
     * @var AttributeGroupService
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
        $paginator = $this->attributeManager->attributeGroups()->dataGetPaginatedResults($request->per_page);
        return $this->respondWithCollection($paginator, new AttributeGroupTransformer);
    }

    /**
     * Handles the request to show a channel based on it's hashed ID
     * @param  String $id
     * @return Json
     */
    public function show($id)
    {
        try {
            $attribute = $this->attributeManager->attributeGroups()->dataGetByHashedId($id);
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($attribute, new AttributeGroupTransformer);
    }

    /**
     * Handles the request to create a new channel
     * @param  CreateRequest $request
     * @return Json
     */
    public function store(CreateRequest $request)
    {
        $result = $this->attributeManager->attributeGroups()->create($request->all());
        return $this->respondWithItem($result, new AttributeGroupTransformer);
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
            $result = $this->attributeManager->attributeGroups()->update($id, $request->all());
        } catch (MinimumRecordRequiredException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($result, new AttributeGroupTransformer);
    }

    public function reorder(ReorderRequest $request)
    {
        try {
            $result = $this->attributeManager->attributeGroups()->updateGroupPositions($request->all());
        } catch (HttpException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (DuplicateValueException $e) {
            return $this->errorWrongArgs($e->getMessage());
        }
        return $this->respondWithNoContent();
    }
    /**
     * Handles the request to delete a channel
     * @param  String        $id
     * @param  DeleteRequest $request
     * @return Json
     */
    public function destroy($id, Request $request)
    {
        try {
            $result = $this->attributeManager
            ->attributeGroups()
            ->deleteByHashedId(
                $id,
                $request->group_id,
                $request->delete_attributes
            );
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        } catch (HttpException $e) {
            return $this->setStatusCode($e->getStatusCode())->respondWithError($e->getMessage());
        }
        return $this->respondWithNoContent();
    }
}
