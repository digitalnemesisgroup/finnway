<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
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

    'razorpay' => [
        'key_id' => env('RAZORPAY_KEY_ID', 'rzp_test_mockkey12345'),
        'key_secret' => env('RAZORPAY_KEY_SECRET', 'mocksecret1234567890'),
        'webhook_secret' => env('RAZORPAY_WEBHOOK_SECRET', 'mockwebhooksecret1234567890'),
    ],

    'cashfree' => [
        'key_id'     => env('CASHFREE_KEY_ID', 'demo'),
        'key_secret' => env('CASHFREE_KEY_SECRET', 'demo'),
        'mode'       => env('CASHFREE_MODE', 'sandbox'), // 'sandbox' or 'production'
    ],

    'phonepe' => [
        'merchant_id'    => env('PHONEPE_MERCHANT_ID', 'PGTESTPAYUAT'),
        'client_id'      => env('PHONEPE_CLIENT_ID', 'CLIENT123'),
        'client_version' => env('PHONEPE_CLIENT_VERSION', 1),
        'client_secret'  => env('PHONEPE_CLIENT_SECRET', 'SECRET123'),
        'webhook_user'   => env('PHONEPE_WEBHOOK_USERNAME', 'webhook_user'),
        'webhook_pass'   => env('PHONEPE_WEBHOOK_PASSWORD', 'webhook_pass'),
        'env'            => env('PHONEPE_ENV', 'PRODUCTION'), // 'PRODUCTION' is required for SDK
    ],

];
