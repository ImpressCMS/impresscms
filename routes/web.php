<?php

declare(strict_types=1);

use App\Http\Controllers\HealthController;
use App\Http\Controllers\LegacyBridgeController;
use App\Http\Controllers\WhoamiController;
use App\Routing\RouteOwnership;
use Illuminate\Support\Facades\Route;

/*
 * Laravel routes and legacy ImpressCMS share one URL space without a prefix
 * (design L4). A route can claim a legacy URL, also a `.php` path, and with
 * whereQuery() only some variants of it. Everything no route claims falls
 * through to the legacy bridge, registered last.
 */

Route::get('/up', HealthController::class)->name('up');

if (app()->environment('local', 'test')) {
    Route::get('/whoami', WhoamiController::class)
        ->middleware(['icms', 'auth', 'can:webmaster'])
        ->name('whoami');
}

if (app()->environment('test')) {
    require __DIR__ . '/test.php';
}

foreach (config('icms.legacy.reserved_prefixes') as $prefix) {
    Route::any(trim($prefix, '/') . '/{rest?}', static fn () => abort(404))
        ->where('rest', '.*')
        ->name(RouteOwnership::RESERVED_ROUTE_PREFIX . $prefix);
}

Route::any('{path}', LegacyBridgeController::class)
    ->where('path', '.*')
    ->fallback()
    ->name(RouteOwnership::LEGACY_ROUTE);
