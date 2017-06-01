<?php

namespace GetCandy\Api\Auth\Services;

use GetCandy\Api\Auth\Models\User;
use GetCandy\Api\Scaffold\BaseService;

class UserService extends BaseService
{
    public function __construct()
    {
        $this->model = new User();
    }

    /**
     * Creates a resource from the given data
     *
     * @param  array  $data
     *
     * @return GetCandy\Api\Auth\Models\User
     */
    public function create($data)
    {
        $user = new User();
        $user->password = bcrypt($data['password']);
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->save();
        return $user;
    }
}
