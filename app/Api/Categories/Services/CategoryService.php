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
        $items = $this->model->withDepth()->defaultOrder()->get()->toTree();
        return $items;
    }

    public function getByParentID($encodedParentID)
    {
        $parentID = $this->model->decodeId($encodedParentID);

        $items = $this->model->where('parent_id', $parentID)->defaultOrder()->get();

        return $items;
    }

    public function create(array $data)
    {

        $category = $this->model;

        $category->attribute_data = $category->parseAttributeData($data['attributes']);

        $category->save();

        if(!empty($data['parent-id'])) {
            $parentNode = $this->getByHashedId($data['parent-id']);
            $parentNode->prependNode($category);
        }

        return $this->getNestedList();

    }

    public function reorder(array $data)
    {
        $response = false;

        $node = $this->getByHashedId($data['node']);
        $movedNode = $this->getByHashedId($data['moved-node']);
        $action = $data['action'];

        switch ($action) {
            case 'before':
                $response = $movedNode->insertBeforeNode($node);
                break;
            case 'after':
                $response = $movedNode->insertAfterNode($node);
                break;
            case 'over':
                $response = $node->prependNode($movedNode);
                break;
        }

        return $response;

    }

    public function uniqueAttribute($key, $value, $channel = 'ecommerce', $lang = 'en')
    {
        $response = true;
        $categories = $this->getNestedList();

        foreach($categories as $category) {
            if(isset($category->attribute_data[$key][$channel][$lang]) && $category->attribute_data[$key][$channel][$lang] === $value) {
                $response = false;
            }
        }

        return $response;
    }

}
