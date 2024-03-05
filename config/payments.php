<?php

declare(strict_types=1);

return [
    /*
     * |--------------------------------------------------------------------------
     * | Paypal Configuration
     * |--------------------------------------------------------------------------
     * |
     * | Here you may configure your paypal settings. Shopper uses paypal to
     * | process payments. You can obtain your paypal credentials from your
     * | paypal account.
     */

    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'client_secret' => env('PAYPAL_CLIENT_SECRET'),
        'currency' => env('PAYPAL_CURRENCY', 'USD'),
        'test_mode' => env('PAYPAL_TEST_MODE', true),
        'currency_conversion_rate' => (float) env('PAYPAL_CURRENCY_CONVERSION_RATE', 1.0),
    ],

    /*
     * |--------------------------------------------------------------------------
     * | Stripe Configuration
     * |--------------------------------------------------------------------------
     * |
     * | Here you may configure your stripe settings. Shopper uses stripe to
     * | process payments. You can obtain your stripe credentials from your
     * | stripe account.
     */

    'stripe' => [
        'publishable_key' => env('STRIPE_PUBLISHABLE_KEY'),
        'secret_key' => env('STRIPE_SECRET_KEY'),
        'currency' => env('STRIPE_CURRENCY', 'USD'),
    ],
];
