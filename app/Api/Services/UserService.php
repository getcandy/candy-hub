<?php

namespace GetCandy\Api\Services;

use GetCandy\Api\Models\User;

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
     * @return GetCandy\Api\Models\User
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
