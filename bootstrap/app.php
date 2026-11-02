<?php

declare(strict_types=1);

use App\Console\Commands\RoutesOwnershipCommand;
use App\Http\HostKernel;
use Illuminate\Contracts\Http\Kernel as HttpKernelContract;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\TrimStrings;

$app = Application::configure(basePath: dirname(__DIR__))
    // No `web:` file: Laravel would wrap it in the `web` group (cookie
    // encryption, Laravel sessions and CSRF), which belongs to ImpressCMS here.
    ->withRouting(
        using: static function (): void {
            require dirname(__DIR__) . '/routes/web.php';
        },
    )
    ->withCommands([RoutesOwnershipCommand::class])
    ->withMiddleware(static function (Middleware $middleware): void {
        // A route that takes over a legacy URL must see the input legacy saw.
        $middleware->remove([TrimStrings::class, ConvertEmptyStringsToNull::class]);
    })
    ->withExceptions(static function (Exceptions $exceptions): void {
    })
    ->create();

$app->useStoragePath($app->basePath('var'));
$app->loadEnvironmentFrom('.env.local');
$app->singleton(HttpKernelContract::class, HostKernel::class);

return $app;
