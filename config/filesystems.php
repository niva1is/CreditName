<?php

return [

    'default' => env('FILESYSTEM_DISK', 'local'),

    'disks' => [

        // 👇 ДИСК ДЛЯ ОБЫЧНЫХ ФАЙЛОВ (НЕ ДОКУМЕНТОВ)
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        // 👇 ДИСК ДЛЯ ПУБЛИЧНЫХ ФАЙЛОВ
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => rtrim(env('APP_URL', 'http://localhost'), '/') . '/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        // 👇 ДИСК ДЛЯ КОНФИДЕНЦИАЛЬНЫХ ДОКУМЕНТОВ (ВАЖНО!)
        'private' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'visibility' => 'private',
            'throw' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],

    ],

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];