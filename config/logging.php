<?php

declare(strict_types=1);

return [
    'default' => env('LOG_CHANNEL', 'single'),

    'channels' => [
        'single' => [
            'driver' => 'single',
            'path' => storage_path('log/' . env('APP_ENV', 'production') . '.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],
    ],
];
