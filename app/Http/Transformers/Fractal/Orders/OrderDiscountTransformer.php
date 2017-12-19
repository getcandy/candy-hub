<?php
namespace GetCandy\Http\Transformers\Fractal\Orders;

use Carbon\Carbon;
use GetCandy\Api\Orders\Models\OrderLine;
use GetCandy\Api\Orders\Models\OrderDiscount;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use GetCandy\Http\Transformers\Fractal\Products\ProductVariantTransformer;

class OrderDiscountTransformer extends BaseTransformer
{
    protected $availableIncludes = [
        'order'
    ];

    public function transform(OrderDiscount $discount)
    {
        $data = [
            'id' => $discount->encodedId(),
            'coupon' => $discount->coupon,
            'amount' => $discount->amount,
            'type' => $discount->type
        ];
        return $data;
    }

    protected function includeVariant(OrderDiscount $discount)
    {
        return $this->item($discount->order, new OrderTransformer);
    }
}
