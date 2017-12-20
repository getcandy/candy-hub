<?php
namespace GetCandy\Http\Transformers\Fractal\Shipping;

use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use Illuminate\Database\Eloquent\Model;
use GetCandy\Api\Shipping\Models\ShippingZone;
use GetCandy\Http\Transformers\Fractal\Countries\CountryTransformer;

class ShippingZoneTransformer extends BaseTransformer
{
    protected $availableIncludes = [
        'countries'
    ];

    public function transform(ShippingZone $zone)
    {
        return [
            'id' => $zone->encodedId(),
            'name' => $zone->name
        ];
    }

    public function includeCountries(ShippingZone $zone)
    {
        return $this->collection($zone->countries, new CountryTransformer);
    }
}
