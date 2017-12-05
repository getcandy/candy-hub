<?php
namespace GetCandy\Http\Transformers\Fractal\Orders;

use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use GetCandy\Api\Orders\Models\Order;
use Carbon\Carbon;
use GetCandy\Http\Transformers\Fractal\Users\UserTransformer;
use GetCandy\Http\Transformers\Fractal\Baskets\BasketTransformer;
use GetCandy\Http\Transformers\Fractal\Orders\OrderLineTransformer;

class OrderTransformer extends BaseTransformer
{
    protected $availableIncludes = [
        'lines', 'user', 'basket'
    ];

    public function transform(Order $order)
    {
        $data = [
            'id' => $order->encodedId()
        ];
        return $data;
    }

    protected function includeLines(Order $order)
    {
        return $this->collection($order->lines, new OrderLineTransformer);
    }

    protected function includeBasket(Order $order)
    {
        return $this->item($order->basket, new BasketTransformer);
    }

    protected function includeUser(Order $order)
    {
        if (!$order->user) {
            return null;
        }
        return $this->item($order->user, new UserTransformer);
    }
}
