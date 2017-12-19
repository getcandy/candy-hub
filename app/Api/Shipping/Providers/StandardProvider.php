<?php

namespace GetCandy\Api\Shipping\Providers;

class StandardProvider extends AbstractProvider
{
    public function calculate($order)
    {
        $basket = $order->basket;
        $weight = $basket->weight;

        $price = $this->method->prices->filter(function ($item) use ($weight) {
            if ($weight > $item->min_weight) {
                return $item;
            };
        })->sortByDesc('min_weight')->first();
        return $price;
    }
}
