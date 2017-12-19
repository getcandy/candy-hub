<?php
namespace GetCandy\Http\Transformers\Fractal\Orders;

use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use GetCandy\Api\Orders\Models\Order;
use Carbon\Carbon;
use GetCandy\Http\Transformers\Fractal\Users\UserTransformer;
use GetCandy\Http\Transformers\Fractal\Baskets\BasketTransformer;
use GetCandy\Http\Transformers\Fractal\Orders\OrderLineTransformer;
use GetCandy\Http\Transformers\Fractal\Payments\TransactionTransformer;

class OrderTransformer extends BaseTransformer
{
    protected $availableIncludes = [
        'lines', 'user', 'basket', 'transactions', 'discounts'
    ];

    public function transform(Order $order)
    {
        $data = [
            'id' => $order->encodedId(),
            'total' => $order->total,
            'vat' => $order->vat,
            'currency' => $order->currency,
            'shipping_total' => $order->shipping_total,
            'shipping_method' => $order->shipping_method,
            'billing' => $order->billing_details,
            'shipping' => $order->shipping_details,
            'status' => $order->status
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

    protected function includeDiscounts(Order $order)
    {
        return $this->collection($order->discounts, new OrderDiscountTransformer);
    }

    protected function includeUser(Order $order)
    {
        if (!$order->user) {
            return null;
        }
        return $this->item($order->user, new UserTransformer);
    }

    protected function includeTransactions(Order $order)
    {
        return $this->collection($order->transactions, new TransactionTransformer);
    }
}
