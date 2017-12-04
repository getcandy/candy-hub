<?php

return [
    /**
     * List which roles have access to the hub
     */
    'hub_access' => ['editor'],

    'discounters' => [
        'coupon' => GetCandy\Api\Discounts\Criteria\Coupon::class,
        'customer-group' => GetCandy\Api\Discounts\Criteria\CustomerGroup::class,
        'product-in-list' => GetCandy\Api\Discounts\Criteria\ProductIn::class,
        'user-in-list' => GetCandy\Api\Discounts\Criteria\UserIn::class
    ]
];
