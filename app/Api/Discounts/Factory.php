<?php

namespace GetCandy\Api\Discounts;

class Factory
{
    protected $rewards = [];

    public function getApplied($discounts, $user, $product = null, $basket = null)
    {
        foreach ($discounts as $index => $discount) {
            foreach ($discount->getCriteria() as $criteria) {
                $fail = 1;
                $pass = 1;

                if (!$criteria->process($user, $product, $basket)) {
                    $fail++;
                } else {
                    $pass++;
                }
                if ($criteria->scope == 'any' && $pass) {
                    $discount->applied = true;
                } elseif ($criteria->scope == 'all' && ($criteria->count() == $pass)) {
                    $discount->applied = true;
                } else {
                    $discount->applied = false;
                }
            }
            if ($discount->stop) {
                break;
            }
        }
        return collect($discounts)->filter(function ($discount) {
            return $discount->applied;
        });
    }

    public function apply($discounts, $product)
    {
        $product->discounts = [];
        $product->original_max_price = $product->max_price;
        $product->original_min_price = $product->min_price;

        $labels = [];

        foreach ($discounts as $index => $discount) {

            $model = $discount->getModel();

            $labels[] = [
                'name' => $model->name,
                'description' => $model->description
            ];

            foreach ($discount->getRewards() as $reward) {
                switch ($reward['type']) {
                    case 'percentage_amount':
                        $product->max_price = $this->applyPercentage($product->max_price, $reward['payload']['value']);
                        $product->min_price = $this->applyPercentage($product->min_price, $reward['payload']['value']);
                        break;
                    case 'fixed_amount':
                        $product->max_price = $this->applyFixedAmount($product->max_price, $reward['payload']['value']);
                        $product->min_price = $this->applyFixedAmount($product->min_price, $reward['payload']['value']);
                        break;
                    case 'to_fixed_price':
                        $product->max_price = $this->applyToFixedAmount($product->max_price, $reward['payload']['value']);
                        $product->min_price = $this->applyToFixedAmount($product->min_price, $reward['payload']['value']);
                        break;
                    default:
                        //
                }
            }
        }

        $product->setAttribute('discounts', $labels);
        
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
