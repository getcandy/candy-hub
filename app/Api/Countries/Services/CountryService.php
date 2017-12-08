<?php
namespace GetCandy\Api\Countries\Services;

use GetCandy\Api\Scaffold\BaseService;

class CountryService extends BaseService
{

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
