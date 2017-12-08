<?php
namespace GetCandy\Http\Transformers\Fractal\Shipping;

use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use Illuminate\Database\Eloquent\Model;
use GetCandy\Api\Shipping\Models\ShippingMethod;

class ShippingMethodTransformer extends BaseTransformer
{
    protected $availableIncludes = [
        'zones', 'prices'
    ];

    public function transform(ShippingMethod $method)
    {
        return [
            'id' => $method->encodedId(),
            'attribute_data' => $method->attribute_data
        ];
    }

    protected function includePrices($method)
    {
        return $this->collection($method->prices, new ShippingPriceTransformer);
    }

    protected function includeZones(ShippingMethod $method)
    {
        return $this->collection($method->zones, new ShippingZoneTransformer);
    }
}
