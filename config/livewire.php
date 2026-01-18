<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Livewire Asset URL
    |--------------------------------------------------------------------------
    |
    | This value sets the path that Livewire will serve its JavaScript assets
    | from. If you are running your application in a subdirectory, you may
    | need to configure this value to match your application's base path.
    |
    | For subfolder deployments, set this to the FULL path to the Livewire JS
    | endpoint including the subfolder prefix (e.g., '/mardini/public/livewire/livewire.js')
    |
    */

    'asset_url' => env('LIVEWIRE_ASSET_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Livewire App URL
    |--------------------------------------------------------------------------
    |
    | This value should be set to the root URL of your application so that
    | it can properly generate URLs to your application's routes. This is
    | used when the application is placed in a subdirectory.
    |
    */

    'app_url' => env('APP_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Livewire Endpoint Middleware
    |--------------------------------------------------------------------------
    |
    | This array of middleware will be applied to Livewire's internal endpoint
    | that handles component updates. Typically, you should include middleware
    | that verifies your user's authentication since this endpoint can be used
    | to call Livewire component methods.
    |
    */

    'middleware_group' => 'web',

    /*
    |--------------------------------------------------------------------------
    | Livewire Temporary File Uploads Endpoint Configuration
    |--------------------------------------------------------------------------
    |
    | Livewire handles file uploads by storing them temporarily in a local
    | temporary directory. All file uploads are directed to a global endpoint
    | for temporary storage. The configuration below can be used to customize
    | the global endpoint's "path" and the "middleware" that guards it.
    |
    */

    'temporary_file_upload' => [
        'disk' => null,        // Example: 'local', 's3'              | Default: 'default'
        'rules' => null,       // Example: ['file', 'mimes:png,jpg']  | Default: ['required', 'file', 'max:12288'] (12MB)
        'directory' => null,   // Example: 'tmp'                      | Default: 'livewire-tmp'
        'middleware' => null,  // Example: 'throttle:5,1'             | Default: 'throttle:60,1'
        'preview_mimes' => [   // Supported file types for temporary pre-signed file URLs.
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
            'mov', 'avi', 'wmv', 'mp3', 'm4a',
            'jpg', 'jpeg', 'mpga', 'webp', 'wma',
        ],
        'max_upload_time' => 5, // Max duration (in minutes) before an upload gets invalidated.
    ],

];
