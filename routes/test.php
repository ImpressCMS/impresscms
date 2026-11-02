<?php

declare(strict_types=1);

use App\Http\Controllers\Test\LegacyConfigurationController;
use App\Http\Controllers\Test\LegacyHandlerController;
use App\Http\Controllers\Test\TakeoverNotSuppressed;
use App\Http\Controllers\Test\UsersTakeoverController;
use Illuminate\Support\Facades\Route;

/*
 * Test-only routes, registered when APP_ENV is `test`.
 */

Route::get('/modules/system/admin.php', UsersTakeoverController::class)
    ->whereQuery(['fct' => 'users'])
    ->matching(TakeoverNotSuppressed::class)
    ->name('test.takeover.system_users');

Route::get('/_test/legacy/sitename', LegacyConfigurationController::class)
    ->middleware('icms.legacy:siteState')
    ->name('test.legacy.sitename');

Route::get('/_test/legacy/handler', LegacyHandlerController::class)
    ->middleware('icms.legacy:module')
    ->name('test.legacy.handler');
