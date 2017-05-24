<?php

namespace GetCandy\Api\Repositories\Eloquent;

use GetCandy\Api\Contracts\RepositoryContract;
use GetCandy\Api\Models\AttributeGroup;

class AttributeGroupRepository extends BaseRepository implements RepositoryContract
{
    public function __construct()
    {
        $this->label = 'name';
        $this->model = new AttributeGroup();
    }

    public function getAllByPosition()
    {
        return $this->model->orderBy('position', 'asc')->get();
    }

    public function getPaginatedResults($length = 50)
    {
        $paginator = $this->model->orderBy('position', 'asc')->paginate($length);
        return $paginator;
    }
}
