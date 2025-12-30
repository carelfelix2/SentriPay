<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Livewire Configuration
    |--------------------------------------------------------------------------
    */

    'layout' => 'layouts.app',

    'asset_url' => null,

    'app_url' => null,

    'asset_url' => null,

    'middleware_group' => 'web',

    'view_path' => resource_path('views/livewire'),

    'class_namespace' => 'App\\Livewire',

    'js_asset_url' => null,

    'js_path' => 'js',

    'temp_file_upload_path' => 'livewire-tmp',

    'temp_file_upload_disk' => 'local',

    'max_file_upload_size' => 12 * 1024 * 1024, // 12MB

    'max_livewire_requests_per_second' => null,

    'max_livewire_requests_per_second_exceed_middleware' => [
        \Livewire\Middleware\RateLimitRequests::class,
    ],

    'offline_fallback_url' => '/offline',

    'offline_fallback_view' => 'livewire::offline',

    'script_data_variable_name' => 'window.Livewire',

    'legacy_mode_enabled' => false,

    'navigate' => [
        'enabled' => true,
    ],

    'morphdom' => [
        'enabled' => true,
        'script_src' => null,
    ],

    'safe_eval' => false,

    'auto_inject_assets' => true,

    'inject_assets' => null,

    'hash' => env('APP_HASH', 'sha256'),

];
