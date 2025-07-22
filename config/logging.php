<?php

return [
    'default' => env('LOG_CHANNEL', 'stack'),

    'deprecations' => [
        'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),
        'trace' => false,
    ],

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single', 'structured'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'structured' => [
            'driver' => 'single',
            'path' => storage_path('logs/structured.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'formatter' => App\Logging\StructuredFormatter::class,
        ],

        'api' => [
            'driver' => 'single',
            'path' => storage_path('logs/api.log'),
            'level' => 'info',
            'formatter' => App\Logging\StructuredFormatter::class,
        ],

        'auth' => [
            'driver' => 'single',
            'path' => storage_path('logs/auth.log'),
            'level' => 'info',
            'formatter' => App\Logging\StructuredFormatter::class,
        ],
    ],
];
