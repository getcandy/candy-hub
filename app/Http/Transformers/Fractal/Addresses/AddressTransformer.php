<?php

namespace GetCandy\Http\Transformers\Fractal\Addresses;

use GetCandy\Api\Associations\Models\AssociationGroup;
use GetCandy\Http\Transformers\Fractal\Attributes\AttributeTransformer;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use GetCandy\Api\Addresses\Models\Address;

class AddressTransformer extends BaseTransformer
{
    public function transform(Address $address)
    {
        return [
            'id' => $address->encodedId(),
            'firstname' => $address->firstname,
            'lastname' => $address->lastname,
            'email' => $address->email,
            'address' => $address->address,
            'address_two' => $address->address_two,
            'address_three' => $address->address_three,
            'city' => $address->city,
            'state' => $address->state,
            'country' => $address->country,
            'zip' => $address->zip,
            'billing' => (bool) $address->billing,
            'shipping' => (bool) $address->shipping,
        ];
    }
}
