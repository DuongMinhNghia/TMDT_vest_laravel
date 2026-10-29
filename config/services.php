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

    'manual_transfer' => [
        'bank_id' => env('MANUAL_TRANSFER_BANK_ID', 'MB'),
        'bank_name' => env('MANUAL_TRANSFER_BANK_NAME', 'MBBank'),
        'account_number' => env('MANUAL_TRANSFER_ACCOUNT_NUMBER'),
        'account_name' => env('MANUAL_TRANSFER_ACCOUNT_NAME'),
        'demo_divisor' => max(1, (int) env('MANUAL_TRANSFER_DEMO_DIVISOR', 1)),
        'allow_demo_in_production' => env('MANUAL_TRANSFER_ALLOW_DEMO_IN_PRODUCTION', false),
    ],

    'google_maps' => [
        'places_api_key' => env('GOOGLE_MAPS_PLACES_API_KEY'),
    ],

];
