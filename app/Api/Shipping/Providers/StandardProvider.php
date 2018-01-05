<?php

namespace GetCandy\Api\Shipping\Providers;

class StandardProvider extends AbstractProvider
{
    public function calculate($order)
    {
        $basket = $order->basket;
        $weight = $basket->weight;
        $total = $basket->total;

        $price = $this->method->prices->filter(function ($item) use ($weight, $total) {
            if ($total > $item->min_basket && $weight >= $item->min_weight) {
                return $item;
            };
        })->sortBy('rate')->first();

        return $price;
    }
}
