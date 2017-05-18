<?php

namespace GetCandy\Api\Attributes\Repositories;

use GetCandy\Api\Attributes\Models\AttributeGroup;
use GetCandy\Api\Scaffold\BaseRepository;

class AttributeGroupRepository extends BaseRepository
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
