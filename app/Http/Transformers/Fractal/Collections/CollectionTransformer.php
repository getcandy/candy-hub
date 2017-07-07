<?php

namespace GetCandy\Http\Transformers\Fractal\Collections;

use GetCandy\Api\Collections\Models\Collection;
use GetCandy\Api\Products\Models\Product;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;

class CollectionTransformer extends BaseTransformer
{
    /**
     * @var Array
     */
    protected $availableIncludes = [
        'products'
    ];

    /**
     * Decorates the product object for viewing
     * @param  Collection $collection
     * @return Array
     */
    public function transform(Collection $collection)
    {
        return [
            'id' => $collection->encodedId(),
            'attribute_data' => $collection->attribute_data
        ];
    }

    /**
     * Includes the products for the collection
     * @param  Collection $collection
     * @return League\Fractal\Resource\Collection
     */
    public function includeProducts(Collection $collection)
    {
        return $this->collection($collection->products, new ProductTransformer);
    }
}
