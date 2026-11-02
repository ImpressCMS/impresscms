<?php

declare(strict_types=1);

/*
 * Laravel's default `database` store would open the ImpressCMS connection
 * (and boot legacy) whenever the cache is resolved, also by artisan.
 */
return [
    'default' => env('CACHE_STORE', 'file'),
];
