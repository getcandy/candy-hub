<?php

namespace GetCandy\Http\Transformers\Fractal\Products;

use GetCandy\Api\Attributes\Models\Attribute;
use GetCandy\Api\Attributes\Models\AttributeGroup;
use GetCandy\Api\Currencies\Models\Currency;
use GetCandy\Api\Languages\Models\Language;
use GetCandy\Api\Products\Models\Product;
use GetCandy\Api\Products\Models\ProductVariant;
use GetCandy\Http\Transformers\Fractal\Assets\AssetTransformer;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;

class ProductVariantTransformer extends BaseTransformer
{
    protected $availableIncludes = [
        'product'
    ];

    public function transform(ProductVariant $variant)
    {
        $response = [
            'id' => $variant->encodedId(),
            'sku' => $variant->sku,
            'backorder' => (bool) $variant->backorder,
            'requires_shipping' => (bool) $variant->requires_shipping,
            'price' => $variant->price,
            'inventory' => $variant->stock,
            'thumbnail' => $this->getThumbnail($variant),
            'weight' => [
                'value' => $variant->weight_value,
                'unit' => $variant->weight_unit
            ],
            'height' => [
                'value' => $variant->height_value,
                'unit' => $variant->height_unit
            ],
            'width' => [
                'value' => $variant->width_value,
                'unit' => $variant->width_unit
            ],
            'depth' => [
                'value' => $variant->depth_value,
                'unit' => $variant->depth_unit
            ],
            'volume' => [
                'value' => $variant->volume_value,
                'unit' => $variant->volume_unit
            ],
            'options' => $variant->options
        ];

        return $response;
    }

    public function includeProduct(ProductVariant $variant)
    {
        return $this->item($variant->product, new ProductTransformer);
    }

    protected function getThumbnail($variant)
    {
        $asset = $variant->image()->count();

        if (!$asset) {
            return null;
        }

        $data = $this->item($variant->image, new AssetTransformer);
        return app()->fractal->createData($data)->toArray();
    }
}
