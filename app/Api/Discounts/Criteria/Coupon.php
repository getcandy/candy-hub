<?php

namespace GetCandy\Api\Discounts\Criteria;

use GetCandy\Api\Discounts\Contracts\DiscountCriteriaContract;

class Coupon implements DiscountCriteriaContract
{
    public function getArea()
    {
        return 'basket';
    }

    public function setCriteria($criteria)
    {
        $this->criteria = json_decode($criteria, true)['value'];
    }

    public function check($user = null, $product = null, $basket = null)
    {
        if (!$basket) {
            return false;
        }
        
        $coupons = $basket->discounts->map(function ($item) {
            return $item->pivot->coupon;
        });

        $check = $coupons->contains($this->criteria);

        return false;
    }

    public function getLabel()
    {
        return 'Coupon code';
    }

    public function getHandle()
    {
        return 'coupon';
    }

    public function getCriteria()
    {
        return [
            [
                '{value}', '===', '{input}'
            ]
        ];
    }
}
