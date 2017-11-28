<?php

return [
    /**
     * List which roles have access to the hub
     */
    'hub_access' => ['editor'],

    'discounters' => [
        'coupon' => GetCandy\Api\Discounts\Criteria\Coupon::class,
        'customer_group' => GetCandy\Api\Discounts\Criteria\CustomerGroup::class,
        'product_in' => GetCandy\Api\Discounts\Criteria\ProductIn::class,
        'user_in' => GetCandy\Api\Discounts\Criteria\UserIn::class
    ]
];
