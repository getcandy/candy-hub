<?php
namespace GetCandy\Http\Transformers\Fractal\Orders;

use Carbon\Carbon;
use GetCandy\Api\Baskets\Models\BasketLine;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use GetCandy\Http\Transformers\Fractal\Products\ProductVariantTransformer;

class OrderLineTransformer extends BaseTransformer
{
    protected $availableIncludes = [
        'variant'
    ];

    public function transform(BasketLine $line)
    {
        $data = [
            'id' => $line->encodedId(),
            'quantity' => $line->quantity,
            'price' => $line->price
        ];
        return $data;
    }

    protected function includeVariant(BasketLine $line)
    {
        return $this->item($line->variant, new ProductVariantTransformer);
    }
}
