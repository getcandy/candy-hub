<?php

namespace GetCandy\Api\Repositories\Eloquent;

use GetCandy\Api\Contracts\RepositoryContract;
use GetCandy\Api\Models\Product;

class ProductRepository extends BaseRepository implements RepositoryContract
{
    public function __construct()
    {
        $this->label = 'name';
        $this->model = new Product();
    }

    public function getPaginatedResults($length = 50, $page = null)
    {
        $paginator = $this->model->with(['attributes', 'attributes.group'])
                        ->paginate($length, ['*'], 'page', $page);
        return $paginator;
    }
}
