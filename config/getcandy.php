<?php

return [
    /**
     * List which roles have access to the hub
     */
    'hub_access' => ['editor'],

    'storefronturl' => env('STOREFRONT_URL'),

    'default_customer_group' => 'retail',

    'discounters' => [
        'coupon' => GetCandy\Api\Discounts\Criteria\Coupon::class,
        'customer-groups' => GetCandy\Api\Discounts\Criteria\CustomerGroup::class,
        'products' => GetCandy\Api\Discounts\Criteria\Products::class,
        'users' => GetCandy\Api\Discounts\Criteria\Users::class,
    ],

    'payments' => [
        'gateway' => 'card',
        'environment' => env('PAYMENT_ENV'),
        'providers' => [
            'account' => GetCandy\Api\Payments\Providers\OnAccount::class,
            'card' => GetCandy\Api\Payments\Providers\Braintree::class,
        ]
    ]
];
