<?php

namespace GetCandy\Http\Transformers\Fractal;

use GetCandy\Api\Products\Models\ProductFamily;

class ProductFamilyTransformer extends BaseTransformer
{
    protected $availableIncludes = [
        'products', 'attributes'
    ];

    public function transform(ProductFamily $family)
    {
        return [
            'id' => $family->encodedId(),
            'name' => $this->getLocalisedName($family->name)
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
