<?php

namespace GetCandy\Http\Transformers\Fractal\Countries;

use GetCandy\Api\Countries\Models\Country;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;

class CountryTransformer extends BaseTransformer
{
    protected $availableIncludes = [];

    public function transform(Country $country)
    {
        return [
            'id' => $country->encodedId(),
            'name' => json_decode($country->name, true),
            'region' => $country->region,
            'iso_a_2' => $country->iso_a_2,
            'iso_a_3' => $country->iso_a_3,
            'iso_numeric' => $country->iso_numeric,
        ];
    }
}
