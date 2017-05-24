<?php

namespace GetCandy\Http\Controllers\Api;

use GetCandy\Http\Transformers\Fractal\UserTransformer;
use GetCandy\Http\Requests\Api\Users\CreateRequest;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    /**
     * Handles the request to show a listing of all users
     * @return Json
     */
    public function index(Request $request)
    {
        $paginator = app('api')->users()->dataGetPaginatedResults($request['per_page']);
        return $this->respondWithCollection($paginator, new UserTransformer);
    }

    /**
     * Handles the request to show a user based on their hashed ID
     * @param  String $id
     * @return Json
     */
    public function show($id)
    {
        $user = app('api')->users()->dataGetByHashedId($id);

        if (!$user) {
            return $this->errorNotFound('Cannot find user');
        }

        return $this->respondWithItem($user, new UserTransformer);
    }

    /**
     * Handles the request to create a new user
     * @param  CreateUserRequest $request
     * @return Json
     */
    public function store(CreateRequest $request)
    {
        $user = app('api')->users()->create($request->all());
        return $this->respondWithItem($user, new UserTransformer);
    }

    public function getCurrentUser()
    {
        return $this->respondWithItem(\Auth::user(), new UserTransformer);
    }

    public function update(UpdateRequest $request)
    {
    }
}
