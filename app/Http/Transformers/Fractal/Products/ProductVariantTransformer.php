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
            'backorder' => (bool) $variant->backorder,
            'requires_shipping' => (bool) $variant->requires_shipping,
            'price' => $variant->price,
            'inventory' => $variant->stock,
            'weight' => [
                'value' => $variant->weight_amount,
                'unit' => $variant->weight_unit
            ],
            'height' => [
                'value' => $variant->height_amount,
                'unit' => $variant->height_unit
            ],
            'width' => [
                'value' => $variant->width_amount,
                'unit' => $variant->width_unit
            ],
            'depth' => [
                'value' => $variant->depth_amount,
                'unit' => $variant->depth_unit
            ],
            'volume' => [
                'value' => $variant->volume_amount,
                'unit' => $variant->volume_unit
            ],
            'options' => $variant->options
        ];

        return $response;
    }
}
