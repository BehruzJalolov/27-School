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

    'sms' => [
        'driver' => env('SMS_DRIVER', 'log'),
    ],

    'eskiz' => [
        'base_url' => env('ESKIZ_BASE_URL', 'https://notify.eskiz.uz/api'),
        'email' => env('ESKIZ_EMAIL'),
        'password' => env('ESKIZ_PASSWORD'),
        'from' => env('ESKIZ_FROM', '4546'),
    ],

    'oneid' => [
        'base_url' => env('ONEID_BASE_URL', 'https://sso.egov.uz'),
        'client_id' => env('ONEID_CLIENT_ID'),
        'client_secret' => env('ONEID_CLIENT_SECRET'),
        'scope' => env('ONEID_SCOPE', 'myportal'),
        'redirect_uri' => env('ONEID_REDIRECT_URI'),
        'endpoints' => [
            'authorization' => env('ONEID_AUTH_ENDPOINT', '/sso/oauth/Authorization.do'),
            'token' => env('ONEID_TOKEN_ENDPOINT', '/sso/oauth/Authorization.do'),
            'user_info' => env('ONEID_USER_INFO_ENDPOINT', '/sso/oauth/Authorization.do'),
        ],
    ],

];
