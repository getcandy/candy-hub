<?php

namespace GetCandy\Api\Shipping\Providers;

class StandardProvider extends AbstractProvider
{
    public function calculate($order)
    {
        $basket = $order->basket;
        $weight = $basket->weight;
        $total = $basket->total;
        $users = $this->method->users;

        $prices = $this->method->prices;
        $price = $prices->filter(function ($item) use ($weight, $total, $order, $users) {
            if ($users->contains($order->user)) {
                return $item;
            } elseif ($users->count()) {
                return false;
            }
            if ($total > $item->min_basket && $weight >= $item->min_weight) {
                return $item;
            };
        })->sortBy('rate')->first();
        
        // dump($price);
        return $price;
    }
}
