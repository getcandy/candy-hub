<?php

namespace GetCandy\Http\Transformers\Fractal;

use GetCandy\Api\Products\Models\ProductFamily;
use League\Fractal\TransformerAbstract;

class ProductFamilyTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'products', 'attributes'
    ];

    public function transform(ProductFamily $family)
    {
        return [
            'id' => $family->encodedId(),
            'name' => $family->name
        ];
    }

    public function includeProducts(ProductFamily $family)
    {
        return $this->collection($family->products, new ProductTransformer);
    }

    public function includeAttributes(ProductFamily $family)
    {
        return $this->collection($family->attributes, new AttributeTransformer);
    }
}
