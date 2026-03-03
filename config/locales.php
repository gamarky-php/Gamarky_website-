<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Available Locales
    |--------------------------------------------------------------------------
    |
    | List of all supported locales in the application
    | Order: Arabic → Chinese → English
    |
    */

    'available' => [
        'ar' => [
            'name' => 'العربية',
            'native' => 'العربية',
            'flag' => '🇸🇦',
            'dir' => 'rtl',
        ],
        'zh' => [
            'name' => 'الصينية',
            'native' => '中文',
            'flag' => '🇨🇳',
            'dir' => 'ltr',
        ],
        'en' => [
            'name' => 'الإنجليزية',
            'native' => 'English',
            'flag' => '🇬🇧',
            'dir' => 'ltr',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Locale
    |--------------------------------------------------------------------------
    */

    'default' => 'ar',

    /*
    |--------------------------------------------------------------------------
    | Fallback Locale
    |--------------------------------------------------------------------------
    */

    'fallback' => 'ar',

    /*
    |--------------------------------------------------------------------------
    | Locale Fonts
    |--------------------------------------------------------------------------
    |
    | Define appropriate font families for each locale
    | Used in PDFs, exports, and dynamic styling
    |
    */

    'fonts' => [
        'ar' => "'DejaVu Sans', 'Arial', sans-serif",
        'zh' => "'SimSun', 'Microsoft YaHei', 'PingFang SC', sans-serif",
        'en' => "'Arial', 'Helvetica', sans-serif",
        'default' => "'DejaVu Sans', 'Arial', sans-serif",
    ],

];
