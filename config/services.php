<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => GetCandy\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'braintree' => [
        'key' => env('BRAINTREE_PUBLIC_KEY'),
        'secret' => env('BRAINTREE_PRIVATE_KEY'),
        '3D_secure' => env('3D_SECURE', false),
        'merchant_id' => env('BRAINTREE_MERCHANT'),
        'merchants' => [
            'default' => env('BRAINTREE_GBP_MERCHANT'),
            'eur' => env('BRAINTREE_EUR_MERCHANT')
        ]
    ]

];
