<?php

namespace GetCandy\Http\Transformers\Fractal;

use GetCandy\Api\Collections\Models\Collection;
use GetCandy\Api\Products\Models\Product;

class CollectionTransformer extends BaseTransformer
{
    protected $availableIncludes = [
        'products'
    ];

    public function transform(Collection $collection)
    {
        return [
            'id' => $collection->encodedId(),
            'attribute_data' => $collection->attribute_data
        ];
    }

    public function products(Collection $collection)
    {
        return $this->collection($collection->products, new ProductTransformer);
    }
}
