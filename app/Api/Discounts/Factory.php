<?php

namespace GetCandy\Api\Discounts;

class Factory
{
    public function process($discounts, $user, $product = null, $basket = null)
    {
        foreach ($discounts as $index => $discount) {
            $discounts[$index]['applied'] = false;
            foreach ($discount['criteria'] as $criteria) {
                $discounts[$index]['applied'] = $criteria->process($user);
            }
        }
        return collect($discounts)->filter(function ($discount) {
            return $discount['applied'];
        });
    }

    public function apply($discounts, $product)
    {
        $product->discounts = [];
        $product->original_max_price = $product->max_price;
        $product->original_min_price = $product->min_price;

        foreach ($discounts as $index => $discount) {
            $product->setAttribute('discounts', [
                $index => [
                    'name' => $discount['name'],
                    'value' => $discount['value'],
                    'type' => $discount['result']
                ]
            ]);
            switch ($discount['result']) {
                case 'percentage_amount':
                    $product->max_price = $this->applyPercentage($product->max_price, $discount['value']);
                    $product->min_price = $this->applyPercentage($product->min_price, $discount['value']);
                    break;
                case 'fixed_amount':
                    $product->max_price = $this->applyFixedAmount($product->max_price, $discount['value']);
                    $product->min_price = $this->applyFixedAmount($product->min_price, $discount['value']);
                    break;
                case 'to_fixed_price':
                    $product->max_price = $this->applyToFixedAmount($product->max_price, $discount['value']);
                    $product->min_price = $this->applyToFixedAmount($product->min_price, $discount['value']);
                    break;
                default:
                    //
            }
        }
    }

    protected function applyPercentage($price, $amount)
    {
        $result = ($price / 100) * $amount;
        return round($price - $result, 2);
    }

    protected function applyFixedAmount($price, $amount) {
        if ($price > $amount) {
            return round($price - $amount, 2);
        }
        return $price;
    }

    protected function applyToFixedAmount($price, $amount) {
        return $amount;
    }
}
