<?php

return [
    /**
     * List which roles have access to the hub
     */
    'hub_access' => ['editor'],

    'default_customer_group' => 'retail',

    'discounters' => [
        'coupon' => GetCandy\Api\Discounts\Criteria\Coupon::class,
        'customer-group' => GetCandy\Api\Discounts\Criteria\CustomerGroup::class,
        'product-in-list' => GetCandy\Api\Discounts\Criteria\ProductIn::class,
        'user-in-list' => GetCandy\Api\Discounts\Criteria\UserIn::class
    ],

    'payments' => [
        'gateway' => 'braintree',
        'environment' => env('PAYMENT_ENV'),
        'providers' => [
            'braintree' => GetCandy\Api\Payments\Providers\Braintree::class
        ]
    ]
];
