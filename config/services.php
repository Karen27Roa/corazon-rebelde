<?php

return [

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

    'google_calendar' => [
        'calendar_id' => env('GOOGLE_CALENDAR_ID'),
        'credentials' => env('GOOGLE_CALENDAR_CREDENTIALS'),
    ],

    // WhatsApp mediante el servicio Node (whatsapp-web.js)
    'whatsapp' => [
        'url'     => env('WA_URL', 'http://127.0.0.1:3000'),
        'token'   => env('WA_TOKEN'),
        'negocio' => env('WA_NEGOCIO'),
    ],

];