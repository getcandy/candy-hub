<?php

namespace GetCandy\Api\Categories\Services;

use GetCandy\Api\Categories\Models\Category;
use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Exceptions\MinimumRecordRequiredException;

class CategoryService extends BaseService
{
    /**
     * @var AttributeGroup
     */
    protected $model;

    public function __construct()
    {
        $this->model = new Category();
    }

    public function getNestedList()
    {
        $items = $this->model->withDepth()->get()->toTree();
        return $items;
    }
}
