<?php

namespace GetCandy\Api\Products\Services;

use GetCandy\Api\Products\Models\Product;
use GetCandy\Api\Products\Models\ProductAssociation;
use GetCandy\Api\Scaffold\BaseService;

class ProductAssociationService extends BaseService
{
    public function __construct()
    {
        $this->model = new Product();
    }

    public function store($product, $data)
    {
        $product = $this->getByHashedId($product);

        foreach ($data['relations'] as $index => $relation) {
            $relation['association'] = $this->getByHashedId($relation['association_id']);
            $relation['type'] = app('api')->associationGroups()->getByHandle($relation['type']);
            $assoc = new ProductAssociation;
            $assoc->group()->associate($relation['type']);
            $assoc->association()->associate($relation['association']);
            $assoc->parent()->associate($product);
            $assoc->save();
        }

        return $product->associations;
    }
}
