<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Stripe Mode
    |--------------------------------------------------------------------------
    |
    | This option controls the default Stripe mode. You can choose between
    | 'test' and 'live'. The mode can be changed from the dashboard or
    | by setting the STRIPE_MODE environment variable.
    |
    */
    'mode' => env('STRIPE_MODE', 'test'),

    /*
    |--------------------------------------------------------------------------
    | Stripe Test Keys
    |--------------------------------------------------------------------------
    |
    | These are the Stripe keys used when the application is in test mode.
    | You can get these keys from your Stripe dashboard under test mode.
    |
    */
    'test' => [
        'key' => env('STRIPE_TEST_KEY'),
        'secret' => env('STRIPE_TEST_SECRET'),
        'webhook_secret' => env('STRIPE_TEST_WEBHOOK_SECRET'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Stripe Live Keys
    |--------------------------------------------------------------------------
    |
    | These are the Stripe keys used when the application is in live mode.
    | You can get these keys from your Stripe dashboard under live mode.
    |
    */
    'live' => [
        'key' => env('STRIPE_LIVE_KEY'),
        'secret' => env('STRIPE_LIVE_SECRET'),
        'webhook_secret' => env('STRIPE_LIVE_WEBHOOK_SECRET'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    |
    | This is the default currency that will be used for Stripe payments.
    | You can override this per transaction if needed.
    |
    */
    'currency' => env('STRIPE_CURRENCY', 'eur'),

    /*
    |--------------------------------------------------------------------------
    | Webhook Tolerance
    |--------------------------------------------------------------------------
    |
    | This option controls the time tolerance for webhook signature verification.
    | The default is 300 seconds (5 minutes).
    |
    */
    'webhook_tolerance' => 300,
];
