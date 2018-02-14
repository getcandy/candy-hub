<?php
namespace GetCandy\Http\Transformers\Fractal\Orders;

use Carbon\Carbon;
use GetCandy\Api\Orders\Models\OrderLine;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use GetCandy\Http\Transformers\Fractal\Products\ProductVariantTransformer;

class OrderLineTransformer extends BaseTransformer
{
    protected $availableIncludes = [
        'variant'
    ];

    public function transform(OrderLine $line)
    {
        $data = [
            'id' => $line->encodedId(),
            'quantity' => $line->quantity,
            'total' => round($line->total, 2),
            'product' => $line->product,
            'sku' => $line->sku,
            'variant' => $line->variant
        ];
        return $data;
    }

    protected function includeVariant(OrderLine $line)
    {
        return $this->item($line->variant, new ProductVariantTransformer);
    }
}
