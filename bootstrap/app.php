<?php

declare(strict_types=1);

use App\Console\Commands\RoutesOwnershipCommand;
use App\Http\HostKernel;
use App\Http\Middleware\RequiresLegacy;
use Icms\Kernel\NotInstalledException;
use Icms\Kernel\SiteClosedException;
use Illuminate\Contracts\Http\Kernel as HttpKernelContract;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
        $middleware->alias(['icms.legacy' => RequiresLegacy::class]);
    })
    ->withExceptions(static function (Exceptions $exceptions): void {
        // A headless boot stops where legacy would exit (design L6).
        $exceptions->dontReport([SiteClosedException::class, NotInstalledException::class]);
        $exceptions->render(static fn (SiteClosedException $e): Response => new Response(
            "<!DOCTYPE html>\n<html><head><meta charset=\"UTF-8\"><title>503 Service Unavailable</title></head>"
            . "<body><h1>Service Unavailable</h1><p>The site is closed for maintenance.</p></body></html>\n",
            Response::HTTP_SERVICE_UNAVAILABLE,
            ['Content-Type' => 'text/html; charset=UTF-8', 'Retry-After' => '3600'],
        ));
        $exceptions->render(static fn (NotInstalledException $e, Request $request): RedirectResponse => new RedirectResponse(
            "{$request->getBasePath()}/install/",
        ));
    })
    ->create();

$app->useStoragePath($app->basePath('var'));
$app->loadEnvironmentFrom('.env.local');
$app->singleton(HttpKernelContract::class, HostKernel::class);

return $app;
