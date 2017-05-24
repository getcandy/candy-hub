<?php

namespace GetCandy\Http\Transformers\Fractal;

use GetCandy\Api\Models\Tax;
use League\Fractal\TransformerAbstract;

class TaxTransformer extends TransformerAbstract
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
