<?php

declare(strict_types=1);

/*
 * Legacy ImpressCMS behind the Laravel host (legacy-request-bridge).
 */
return [
    'legacy' => [
        // htdocs/ of the ImpressCMS tree
        'root' => env('ICMS_LEGACY_ROOT', base_path('htdocs')),

        // executed for legacy-owned paths that do not exist
        'front_controller' => 'index.php',

        // path prefixes that never fall through to legacy, for example ['admin-next']
        'reserved_prefixes' => env('APP_ENV') === 'test' ? ['admin-next'] : [],

        // directories never served or executed (relative to the legacy root,
        // or absolute); ICMS_TRUST_PATH names the trust path if it is under the root
        'non_public_paths' => [
            'templates_c',
            'cache',
            env('ICMS_TRUST_PATH', ''),
        ],

        // directories whose PHP files are never executed
        'script_free_paths' => ['uploads'],

        // static files served directly, with explicit types for web assets
        'asset_extensions' => [
            'avif', 'bmp', 'css', 'csv', 'eot', 'gif', 'htm', 'html', 'ico', 'jpeg', 'jpg', 'js', 'json', 'map',
            'mjs', 'mp3', 'mp4', 'otf', 'pdf', 'png', 'svg', 'swf', 'ttf', 'txt', 'webm', 'webp', 'woff', 'woff2',
            'xml', 'xsl', 'zip',
        ],
        'mime_types' => [
            'avif' => 'image/avif',
            'bmp' => 'image/bmp',
            'css' => 'text/css; charset=UTF-8',
            'csv' => 'text/csv; charset=UTF-8',
            'gif' => 'image/gif',
            'htm' => 'text/html; charset=UTF-8',
            'html' => 'text/html; charset=UTF-8',
            'ico' => 'image/x-icon',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'map' => 'application/json',
            'mjs' => 'application/javascript',
            'otf' => 'font/otf',
            'png' => 'image/png',
            'svg' => 'image/svg+xml',
            'ttf' => 'font/ttf',
            'txt' => 'text/plain; charset=UTF-8',
            'webm' => 'video/webm',
            'webp' => 'image/webp',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'xml' => 'application/xml',
        ],
    ],
];
