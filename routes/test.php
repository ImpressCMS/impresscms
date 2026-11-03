<?php

declare(strict_types=1);

use App\Http\Controllers\Test\IdentityTestController;
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
    ->takeover('test.users')
    ->name('test.takeover.system_users');

Route::get('/_test/legacy/sitename', LegacyConfigurationController::class)
    ->middleware('icms.legacy:siteState')
    ->name('test.legacy.sitename');

Route::get('/_test/legacy/handler', LegacyHandlerController::class)
    ->middleware('icms.legacy:module')
    ->name('test.legacy.handler');

Route::middleware('icms')->controller(IdentityTestController::class)->group(static function (): void {
    Route::get('/_test/identity', 'identity')->name('test.identity');
    Route::get('/_test/members-only', 'membersOnly')->middleware('auth')->name('test.members_only');
    Route::get('/_test/module-admin', 'moduleAdmin')->middleware('can:icms_perm:module_admin')->name('test.module_admin');
    Route::get('/_test/module-read', 'moduleRead')->middleware('can:icms_perm:module_read')->name('test.module_read');
    Route::post('/_test/token', 'token')->middleware('icms.token')->name('test.token');
});
