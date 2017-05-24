<?php

namespace GetCandy\Api\Repositories\Eloquent;

use GetCandy\Api\Models\Product;
use GetCandy\Api\Scaffold\BaseRepository;

class ProductRepository extends BaseRepository
{
    public function __construct()
    {
        $this->label = 'name';
        $this->model = new Product();
    }

    public function getPaginatedResults($length = 50)
    {
        $paginator = $this->model->with(['attributes', 'attributes.group'])->paginate($length);
        return $paginator;
    }
}
