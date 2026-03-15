<?php

return [
    'stripe' => [
        'mode' => env('STRIPE_MODE', 'test'),
        'test_key' => env('STRIPE_TEST_PUBLIC_KEY'),
        'test_secret' => env('STRIPE_TEST_SECRET_KEY'),
        'live_key' => env('STRIPE_LIVE_PUBLIC_KEY'),
        'live_secret' => env('STRIPE_LIVE_SECRET_KEY'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        'currency' => env('STRIPE_CURRENCY', 'eur'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
];
