<?php

namespace GetCandy\Http\Controllers\Api\Assets;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Requests\Api\Assets\UpdateAllRequest;

class AssetController extends BaseController
{
    public function destroy($id)
    {
        try {
            $result = app('api')->assets()->delete($id);
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithNoContent();
    }

    public function updateAll(UpdateAllRequest $request)
    {
        $result = app('api')->assets()->updateAll($request->assets);
        if (!$result) {
            $this->respondWithError();
        }
        return $this->respondWithComplete();
    }
}
