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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'external_apis' => [
        'news_base_url' => env('EXT_NEWS_BASE_URL'),
        'pertare_listings_url' => env('EXT_PERTARE_LISTINGS_URL'),
        'pertare_space_available_url' => env('EXT_PERTARE_SPACE_AVAILABLE_URL'),
        'pertare_listing_detail_url' => env('EXT_PERTARE_LISTING_DETAIL_URL'),
        'service_listing_detail_url' => env('EXT_SERVICE_LISTING_DETAIL_URL'),
    ],

];
