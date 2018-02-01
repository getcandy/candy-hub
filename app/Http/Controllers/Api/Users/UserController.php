<?php

namespace GetCandy\Http\Controllers\Api\Users;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Requests\Api\Users\CreateRequest;
use GetCandy\Http\Requests\Api\Users\UpdateRequest;
use GetCandy\Http\Transformers\Fractal\Users\UserTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    /**
     * Handles the request to show a listing of all users
     * @return Json
     */
    public function index(Request $request)
    {
        $paginator = app('api')->users()->getPaginatedData(
            $request->per_page,
            $request->page,
            $request->keywords,
            $request->ids
        );
        return $this->respondWithCollection($paginator, new UserTransformer);
    }

    /**
     * Handles the request to show a user based on their hashed ID
     * @param  String $id
     * @return Json
     */
    public function show($id)
    {
        $user = app('api')->users()->getByHashedId($id);

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

    public function update(UpdateRequest $request, $userId)
    {
        $user = app('api')->users()->update($userId, $request->all());
        return $this->respondWithItem($user, new UserTransformer);
    }
}
