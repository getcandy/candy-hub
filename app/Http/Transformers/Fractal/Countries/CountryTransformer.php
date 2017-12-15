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
            'name' => json_decode($country->name, true)
        ];
    }
}
