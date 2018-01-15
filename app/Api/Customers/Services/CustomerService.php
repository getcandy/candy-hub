<?php

namespace GetCandy\Api\Customers\Services;

use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Api\Auth\Models\User;

class CustomerService extends BaseService
{
    public function __construct()
    {
        $this->model = new User();
    }

    /**
     * Registers a new customer
     * @param  array  $data
     * @return [type]       [description]
     */
    public function register(array $data)
    {
        $user = app('api')->users()->create($data);
        $user->assignRole('customer');
        return $user;
    }
}
