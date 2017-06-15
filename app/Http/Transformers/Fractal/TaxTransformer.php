<?php

namespace GetCandy\Http\Transformers\Fractal;

use GetCandy\Api\Taxes\Models\Tax;

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
