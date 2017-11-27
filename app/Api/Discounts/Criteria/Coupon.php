<?php

namespace GetCandy\Api\Discounts\Criteria;

use GetCandy\Api\Discounts\Contracts\DiscountCriteriaContract;

class Coupon implements DiscountCriteriaContract
{
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
