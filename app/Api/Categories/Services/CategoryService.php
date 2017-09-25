<?php

namespace GetCandy\Api\Categories\Services;

use GetCandy\Api\Categories\Models\Category;
use GetCandy\Api\Routes\Models\Route;
use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Exceptions\MinimumRecordRequiredException;

class CategoryService extends BaseService
{
    /**
     * @var AttributeGroup
     */
    protected $model;
    protected $route;

    public function __construct()
    {
        $this->model = new Category();
        $this->route = new Route();
    }

    public function getAll()
    {
        return $this->model->get();
    }

    public function getNestedList()
    {
        $categories = $this->model->withDepth()->defaultOrder()->get()->toTree();
        return $categories;
    }

    public function getByParentID($encodedParentID)
    {
        $parentID = $this->model->decodeId($encodedParentID);

        $categories = $this->model->where('parent_id', $parentID)->defaultOrder()->get();

        return $categories;
    }

    public function create(array $data)
    {
        // Create Category
        $category = $this->model;
        $category->attribute_data = $category->parseAttributeData($data['attributes']);
        $category->save();

        // Create Route
        $route = $this->route;
        $route->slug = $data['routes']['slug'];
        $route->default = $data['routes']['default'];
        $route->locale = $data['routes']['locale'];
        $category->routes()->save($route);

        // If a parent id exists then add the category to the parent
        if(!empty($data['parent-id'])) {
            $parentNode = $this->getByHashedId($data['parent-id']);
            $parentNode->prependNode($category);
        }
        return $category;
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
        $categories = $this->getAll();

        foreach($categories as $category) {
            if(isset($category->attribute_data[$key][$channel][$lang]) && $category->attribute_data[$key][$channel][$lang] == $value) {
                return false;
            }
        }
        return true;
    }

}
