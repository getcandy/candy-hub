<?php

namespace GetCandy\Http\Controllers\Api\Associations;

use GetCandy\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use GetCandy\Http\Transformers\Fractal\Associations\AssociationGroupTransformer;

class AssociationGroupController extends BaseController
{

    /**
     * Returns a listing of channels
     * @return Json
     */
    public function index(Request $request)
    {
        $groups = app('api')->associationGroups()->getPaginatedData();
        return $this->respondWithCollection($groups, new AssociationGroupTransformer);
    }
}
