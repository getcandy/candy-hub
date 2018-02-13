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
            'reference' => $order->ref,
            'invoice_reference' => $order->invoice_reference,
            'vat' => $order->vat,
            'vat_no' => $order->vat_no,
            'tracking_no' => $order->tracking_no,
            'dispatched_at' => $order->dispatched_at,
            'currency' => $order->currency,
            'shipping_total' => $order->shipping_total,
            'shipping_method' => $order->shipping_method,
            'customer_name' => $order->customer_name,
            'contact_phone' => $order->contact_phone,
            'contact_email' => $order->contact_email,
            'billing' => $order->billing_details,
            'shipping' => $order->shipping_details,
            'status' => $order->status,
            'created_at' => $order->created_at,
            'notes' => $order->notes
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
