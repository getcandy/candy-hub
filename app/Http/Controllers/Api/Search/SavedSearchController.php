<?php

namespace GetCandy\Http\Controllers\Api\Search;

use GetCandy\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use GetCandy\Http\Requests\Api\Search\StoreRequest;
use GetCandy\Http\Transformers\Fractal\Search\SavedSearchTransformer;

class SavedSearchController extends BaseController
{
    public function store(StoreRequest $request)
    {
        $search = app('api')->savedSearch()->store($request->all());
        return $this->respondWithItem($search, new SavedSearchTransformer);
    }

    public function getByType($type, Request $request)
    {
        $result = app('api')->savedSearch()->getByType($type);

        return $this->respondWithCollection($result, new SavedSearchTransformer);
    }

    public function destroy($id)
    {
        try {
            $result = app('api')->savedSearch()->delete($id);
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
    }
}
