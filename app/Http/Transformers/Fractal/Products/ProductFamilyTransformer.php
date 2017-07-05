<?php

namespace GetCandy\Http\Transformers\Fractal\Products;

use GetCandy\Api\Products\Models\ProductFamily;
use GetCandy\Http\Transformers\Fractal\Attributes\AttributeTransformer;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;

class ProductFamilyTransformer extends BaseTransformer
{
    protected $availableIncludes = [
        'products', 'attributes'
    ];

    public function transform(ProductFamily $family)
    {
        return [
            'id' => $family->encodedId(),
            'attribute_data' => $family->attribute_data
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
