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

    public function update(array $data)
    {

        dd($this->model->rebuildTree($data[0]));

        return $this->model->rebuildTree($this->rebuildArray($data[0]));
    }






    public function rebuildArray(array $data)
    {
        $response = [];

        foreach($data as $category){
            if(isset($category->data)){
                $response += [
                    "id" => Hashids::decode($category->id)[0],
                    "attribute_data" => json_encode($category->data->attribute_data),
                    "depth" => $category->data->depth,
                    "children" => (isset($category->data->children) ? $this->rebuildArray($category->data->children) : null)
                ];
            }elseif(isset($category->data)){
                $response += [
                    "id" => Hashids::decode($category->id)[0],
                    "attribute_data" => json_encode($category->attribute_data),
                    "depth" => $category->depth,
                    "children" => (isset($category->children) ? $this->rebuildArray($category->children) : null)
                ];
            }

        }
        return $response;
    }

}
