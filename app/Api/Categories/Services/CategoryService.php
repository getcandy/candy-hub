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

    public function create(array $data)
    {

        $category = $this->model;

        $category->attribute_data = $category->parseAttributeData($data['attributes']);

        $category->save();

        if(isset($data['parent-id'])) {
            $parentNode = $this->getByHashedId($data['parent-id']);
            $parentNode->prependNode($category);
        }

        return $this->getNestedList();

    }

    public function reorder(array $data)
    {
        $response = false;

        $node = $this->getByHashedId($data['id']);
        $nodeKey = array_search($data['id'], $data['siblings']);

        // If node is first and has no parent or siblings append it to the tree
        if(!isset($data['parent-id']) && $nodeKey === 0 && count($data['siblings']) === 1){

            $response = $node->saveAsRoot();

        // If node is first and has no parent but has siblings prepend it to the tree
        }elseif(!isset($data['parent-id']) && $nodeKey === 0 && count($data['siblings']) > 1) {

            $beforeNode = $this->getByHashedId($data['siblings'][$nodeKey +1]);
            $response = $node->insertBeforeNode($beforeNode);

        // If node is not alone and is not the first then place it behind sibling
        }elseif(count($data['siblings']) > 1 && $nodeKey > 0){

            $afterNode = $this->getByHashedId($data['siblings'][$nodeKey - 1]);
            $response = $node->insertAfterNode($afterNode);

        // If node is the first and has a parent we can prepend it to its parent
        }else{

            $parentNode = $this->getByHashedId($data['parent-id']);
            $response = $parentNode->prependNode($node);

        }

        if($response){
            return $this->getNestedList();
        }

        return false;

    }

}
