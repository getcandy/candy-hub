<?php

namespace GetCandy\Api\Services;

use GetCandy\Api\Models\User;
use GetCandy\Api\Repositories\Eloquent\UserRepository;

class UserService
{
    /**
     * @var GetCandy\Api\Repositories\UserRepository
     */
    protected $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
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
        $user->password = \Hash::make($data['password']);
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = $data['role'];
        $user->save();
        return $user;
    }
}
