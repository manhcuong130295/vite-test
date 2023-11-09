<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret_key' => env('STRIPE_SECRET'),
        'stripe_plan' => env('STRIPE_PLAN'),
        'stripe_secret' => env('STRIPE_SECRET'),
        'cashier_currency' => env('CASHIER_CURRENCY'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        'signing_secret' => env('STRIPE_SIGNING_SECRET'),
    ],

    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_REDIRECT'),
    ],

    'open_ai' => [
        'api_key' => env('OPENAI_API_KEY', null)
    ],

    'pinecone' => [
        'api_key' => env('PINECONE_API_KEY', null),
        'environment' => env('PINECONE_ENVIRONMENT', null),
        'pinecone_index' => env('PINECONE_INDEX')
    ],
    'line_bot' => [
        'channel_access_token' => env('LINE_BOT_CHANNEL_ACCESS_TOKEN'),
        'channel_id' => env('LINE_BOT_CHANNEL_ID'),
        'channel_secret' => env('LINE_BOT_CHANNEL_SECRET'),
        'client' => [
            'config' => [
            'headers' => ['X-Foo' => 'Bar'],
            ],
        ],
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
];
