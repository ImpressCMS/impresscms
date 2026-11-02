<?php

declare(strict_types=1);

/*
 * Laravel's default is realpath(storage_path('framework/views')), which is
 * empty until the directory exists; the Blade compiler (used for error pages)
 * then refuses to start. It creates the directory itself when given the path.
 */
return [
    'compiled' => storage_path('framework/views'),
];
