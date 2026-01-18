<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | Central place for credentials of third‑party services used by Gamarky.
    | All secrets are read from .env — do not hardcode values here.
    |
    */

    // === Email Providers (optional) ===
    'mailgun' => [
        'domain'   => env('MAILGUN_DOMAIN'),
        'secret'   => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme'   => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // === Social Login ===
    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_REDIRECT_URI'), // e.g. https://www.gamarky.com/auth/social/google/callback
    ],

    'apple' => [
        'enabled'       => env('APPLE_ENABLED', false),
        'client_id'     => env('APPLE_CLIENT_ID'),
        'client_secret' => env('APPLE_CLIENT_SECRET'),
        'redirect'      => env('APPLE_REDIRECT_URI'),
    ],

    // === Realtime (optional) ===
    'pusher' => [
        'key'     => env('PUSHER_APP_KEY'),
        'secret'  => env('PUSHER_APP_SECRET'),
        'app_id'  => env('PUSHER_APP_ID'),
        'options' => [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS'  => true,
        ],
    ],

    // === Storage (optional; used via filesystems.php) ===
    's3' => [
        'key'                      => env('AWS_ACCESS_KEY_ID'),
        'secret'                   => env('AWS_SECRET_ACCESS_KEY'),
        'region'                   => env('AWS_DEFAULT_REGION'),
        'bucket'                   => env('AWS_BUCKET'),
        'url'                      => env('AWS_URL'),
        'endpoint'                 => env('AWS_ENDPOINT'),
        'use_path_style_endpoint'  => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
    ],

    // === SMS (optional) ===
    'twilio' => [
        'sid'   => env('TWILIO_SID'),
        'token' => env('TWILIO_TOKEN'),
        'from'  => env('TWILIO_FROM'),
        'enabled' => env('TWILIO_ENABLED', false),
    ],

    // === Payment Gateways (optional) ===
    'stripe' => [
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

    'paypal' => [
        'client_id'     => env('PAYPAL_CLIENT_ID'),
        'client_secret' => env('PAYPAL_CLIENT_SECRET'),
        'mode'          => env('PAYPAL_MODE', 'sandbox'), // or 'live'
    ],

    // === Shipping / Tracking Providers (optional; placeholders) ===
    'shipping' => [
        'searates' => [
            'api_key' => env('SEARATES_API_KEY'),
            'api_url' => env('SEARATES_API_URL', 'https://api.searates.com'),
            'enabled' => env('SEARATES_ENABLED', false),
        ],
        'marinetraffic' => [
            'api_key' => env('MARINETRAFFIC_API_KEY'),
            'api_url' => env('MARINETRAFFIC_API_URL', 'https://services.marinetraffic.com'),
            'enabled' => env('MARINETRAFFIC_ENABLED', false),
        ],
        'maersk' => [
            'api_key' => env('MAERSK_API_KEY'),
            'api_url' => env('MAERSK_API_URL', 'https://api.maersk.com'),
            'enabled' => env('MAERSK_ENABLED', false),
        ],
        'msc' => [
            'api_key' => env('MSC_API_KEY'),
            'api_url' => env('MSC_API_URL', 'https://api.msc.com'),
            'enabled' => env('MSC_ENABLED', false),
        ],
        'cma_cgm' => [
            'api_key' => env('CMA_CGM_API_KEY'),
            'api_url' => env('CMA_CGM_API_URL', 'https://api.cma-cgm.com'),
            'enabled' => env('CMA_CGM_ENABLED', false),
        ],
        'cosco' => [
            'api_key' => env('COSCO_API_KEY'),
            'api_url' => env('COSCO_API_URL', 'https://api.cosco.com'),
            'enabled' => env('COSCO_ENABLED', false),
        ],
        'hapag_lloyd' => [
            'api_key' => env('HAPAG_LLOYD_API_KEY'),
            'api_url' => env('HAPAG_LLOYD_API_URL', 'https://api.hapag-lloyd.com'),
            'enabled' => env('HAPAG_LLOYD_ENABLED', false),
        ],
    ],

];
