<?php

return [
    /**
     * List which roles have access to the hub
     */
    'hub_access' => ['editor'],

    'discounters' => [
        'coupon' => GetCandy\Api\Discounts\Criteria\Coupon::class,
        'customer_group' => GetCandy\Api\Discounts\Criteria\CustomerGroup::class
    ]
];
