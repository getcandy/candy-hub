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

    public function getPaginatedData($length = 50, $page = null, $keywords = null)
    {
        $query = $this->model;

        if ($keywords) {
            $query = $query->orWhere('email', 'LIKE', '%'.$keywords.'%')
                        ->orWhere('firstname', 'LIKE', '%'.$keywords.'%')
                        ->orWhere('lastname', 'LIKE', '%' . $keywords . '%');
        }

        return $query->paginate($length, ['*'], 'page', $page);
    }
}
