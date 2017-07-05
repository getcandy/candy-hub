<?php

namespace GetCandy\Http\Transformers\Fractal\Products;

use GetCandy\Api\Attributes\Models\Attribute;
use GetCandy\Api\Attributes\Models\AttributeGroup;
use GetCandy\Api\Currencies\Models\Currency;
use GetCandy\Api\Languages\Models\Language;
use GetCandy\Api\Products\Models\Product;
use GetCandy\Api\Products\Models\ProductVariant;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;

class ProductVariantTransformer extends BaseTransformer
{
    protected $availableIncludes = [];

    public function transform(ProductVariant $variant)
    {
        $response = [
            'id' => $variant->encodedId(),
            'sku' => $variant->sku,
            'attribute_data' => $variant->attribute_data
        ];

        return $response;
    }
}
