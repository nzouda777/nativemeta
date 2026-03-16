<?php

return [
    'stripe' => [
        'mode' => env('STRIPE_MODE', 'test'),
        'test' => [
            'key' => env('STRIPE_TEST_KEY'),
            'secret' => env('STRIPE_TEST_SECRET'),
            'webhook_secret' => env('STRIPE_TEST_WEBHOOK_SECRET'),
        ],
        'live' => [
            'key' => env('STRIPE_LIVE_KEY'),
            'secret' => env('STRIPE_LIVE_SECRET'),
            'webhook_secret' => env('STRIPE_LIVE_WEBHOOK_SECRET'),
        ],
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
