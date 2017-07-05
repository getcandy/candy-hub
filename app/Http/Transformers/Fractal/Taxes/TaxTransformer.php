<?php

namespace GetCandy\Http\Transformers\Fractal\Taxes;

use GetCandy\Api\Taxes\Models\Tax;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;

class TaxTransformer extends BaseTransformer
{
    protected $availableIncludes = [];

    public function transform(Tax $tax)
    {
        return [
            'id' => $tax->encodedId(),
            'name' => $tax->name,
            'percentage' => $tax->percentage
        ];
    }
}
