<?php

namespace GetCandy\Api\Repositories\Eloquent;

use GetCandy\Api\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct()
    {
        $this->label = 'username';
        $this->model = new User();
    }
}
