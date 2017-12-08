<?php
namespace GetCandy\Http\Transformers\Fractal\Shipping;

use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use Illuminate\Database\Eloquent\Model;
use GetCandy\Api\Shipping\Models\ShippingPrice;

class ShippingPriceTransformer extends BaseTransformer
{
    public function transform(ShippingPrice $price)
    {
        return [
            'id' => $price->encodedId(),
            'rate' => $price->rate,
            'fixed' => (bool) $price->fixed,
            'weight' => [
                'value' => $price->min_weight,
                'unit' => $price->weight_unit
            ],
            'height' => [
                'value' => $price->min_height,
                'unit' => $price->height_unit
            ],
            'width' => [
                'value' => $price->min_width,
                'unit' => $price->width_unit
            ],
            'depth' => [
                'value' => $price->min_depth,
                'unit' => $price->depth_unit
            ],
            'volume' => [
                'value' => $price->min_volume,
                'unit' => $price->volume_unit
            ],
        ];
    }
}
