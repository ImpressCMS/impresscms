<?php

declare(strict_types=1);

return [
    'disks' => [
        // Laravel 12 serves this disk under /storage/{path} by default; that
        // URL space belongs to ImpressCMS
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => false,
            'throw' => false,
            'report' => false,
        ],
    ],
];
