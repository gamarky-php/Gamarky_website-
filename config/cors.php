<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie'
    ],

    'allowed_methods' => explode(',', env('CORS_ALLOWED_METHODS', 'GET,HEAD,PUT,PATCH,POST,DELETE,OPTIONS')),

    'allowed_origins' => [
        // Local development
        'http://localhost:*',
        'http://127.0.0.1:*',
        'http://localhost',
        'http://127.0.0.1',
        
        // Mobile development (React Native, Flutter, etc.)
        'http://10.0.2.2:*', // Android emulator
        'http://192.168.*:*', // Local network
        
        // Production (add your domains here)
        // 'https://yourdomain.com',
        // 'https://api.yourdomain.com',
    ],

    'allowed_origins_patterns' => [
        '/^http:\/\/localhost:\d+$/',
        '/^http:\/\/127\.0\.0\.1:\d+$/',
        '/^http:\/\/10\.0\.2\.2:\d+$/', // Android emulator
        '/^http:\/\/192\.168\.\d+\.\d+:\d+$/', // Local network
    ],

    'allowed_headers' => [
        'Accept',
        'Authorization',
        'Content-Type',
        'X-Requested-With',
        'X-CSRF-TOKEN',
        'X-XSRF-TOKEN',
        'Origin',
        'Cache-Control',
        'Pragma',
    ],

    'exposed_headers' => [
        'Cache-Control',
        'Content-Language',
        'Content-Type',
        'Expires',
        'Last-Modified',
        'Pragma',
    ],

    'max_age' => env('CORS_MAX_AGE', 86400), // 24 hours

    'supports_credentials' => env('CORS_SUPPORTS_CREDENTIALS', true),

];