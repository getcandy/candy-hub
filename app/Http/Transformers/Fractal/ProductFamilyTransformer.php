<?php

namespace GetCandy\Http\Transformers\Fractal;

use GetCandy\Api\Models\ProductFamily;
use League\Fractal\TransformerAbstract;

class ProductFamilyTransformer extends TransformerAbstract
{
    protected $availableIncludes = [];

    public function transform(ProductFamily $family)
    {
        return [
            'id' => $family->encodedId(),
            'name' => $family->name
        ];
    }
}
