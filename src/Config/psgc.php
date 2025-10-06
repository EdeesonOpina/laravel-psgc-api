<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PSGC API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for the PSGC API package
    |
    */

    /*
    |--------------------------------------------------------------------------
    | API Settings
    |--------------------------------------------------------------------------
    */
    'api_prefix' => env('PSGC_API_PREFIX', 'api/v1'),
    'middleware' => env('PSGC_MIDDLEWARE', ['api']),

    /*
    |--------------------------------------------------------------------------
    | Database Settings
    |--------------------------------------------------------------------------
    */
    'table_prefix' => env('PSGC_TABLE_PREFIX', ''),
    
    'tables' => [
        'regions' => env('PSGC_REGIONS_TABLE', 'regions'),
        'provinces' => env('PSGC_PROVINCES_TABLE', 'provinces'),
        'city_municipalities' => env('PSGC_CITY_MUNICIPALITIES_TABLE', 'city_municipalities'),
        'barangays' => env('PSGC_BARANGAYS_TABLE', 'barangays'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Data Source Settings
    |--------------------------------------------------------------------------
    */
    'data_source' => [
        'package' => '@jobuntux/psgc',
        'official_source' => 'Philippine Statistics Authority (PSA)',
        'update_frequency' => 'quarterly',
        'latest_version' => '2025-2Q',
    ],

    /*
    |--------------------------------------------------------------------------
    | Import/Export Settings
    |--------------------------------------------------------------------------
    */
    'import' => [
        'default_status' => 'active',
        'batch_size' => 1000,
        'timeout' => 300, // 5 minutes
    ],

    'export' => [
        'default_format' => 'csv',
        'default_status' => 'active',
        'chunk_size' => 1000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'enabled' => env('PSGC_CACHE_ENABLED', true),
        'ttl' => env('PSGC_CACHE_TTL', 3600), // 1 hour
        'prefix' => env('PSGC_CACHE_PREFIX', 'psgc'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */
    'rate_limit' => [
        'enabled' => env('PSGC_RATE_LIMIT_ENABLED', false),
        'max_attempts' => env('PSGC_RATE_LIMIT_MAX_ATTEMPTS', 60),
        'decay_minutes' => env('PSGC_RATE_LIMIT_DECAY_MINUTES', 1),
    ],

    /*
    |--------------------------------------------------------------------------
    | Package Information
    |--------------------------------------------------------------------------
    */
    'package' => [
        'name' => 'PSGC API Philippines',
        'version' => '1.0.0',
        'description' => 'Philippine Standard Geographic Code (PSGC) API package for Laravel',
        'author' => 'Edeeson Opina',
        'author_url' => 'https://edeesonopina.vercel.app/',
        'license' => 'MIT',
        'repository' => 'https://github.com/edeeson/psgc-api',
    ],
];
