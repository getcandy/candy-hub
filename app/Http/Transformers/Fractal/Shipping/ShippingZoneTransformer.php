<?php
namespace GetCandy\Http\Transformers\Fractal\Shipping;

use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use Illuminate\Database\Eloquent\Model;
use GetCandy\Api\Shipping\Models\ShippingZone;

class ShippingZoneTransformer extends BaseTransformer
{
    public function transform(ShippingZone $zone)
    {
        return [
            'id' => $zone->encodedId(),
            'name' => $zone->name
        ];
    }
}
