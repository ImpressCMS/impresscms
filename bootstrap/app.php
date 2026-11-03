<?php

declare(strict_types=1);

use App\Auth\LoginRedirect;
use App\Console\Commands\RoutesOwnershipCommand;
use App\Http\HostKernel;
use App\Http\Middleware\RequiresLegacy;
use App\Http\Middleware\RequiresLegacyToken;
use App\Http\Middleware\StartIcmsSession;
use App\Http\Middleware\SystemAdminFunction;
use Icms\Kernel\NotInstalledException;
use Icms\Kernel\SiteClosedException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Http\Kernel as HttpKernelContract;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
        $middleware->alias([
            'icms.legacy' => RequiresLegacy::class,
            'icms.token' => RequiresLegacyToken::class,
            'icms.admin' => SystemAdminFunction::class,
        ]);
        // instead of `web`: ImpressCMS' session, no cookie encryption, no Laravel CSRF (designs L10, L12)
        $middleware->group('icms', [
            StartIcmsSession::class,
            ShareErrorsFromSession::class,
            SubstituteBindings::class,
        ]);
        // `auth` computes this target up front; it would default to route('login')
        $middleware->redirectGuestsTo(static fn (Request $request): string => LoginRedirect::url($request));
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
        // anonymous visitors who are denied log in through ImpressCMS (design L11)
        $exceptions->render(static fn (AuthenticationException $e, Request $request): RedirectResponse => LoginRedirect::for($request));
        $exceptions->render(static function (AccessDeniedHttpException $e, Request $request): ?RedirectResponse {
            if (! $e->getPrevious() instanceof AuthorizationException) {
                return null;
            }

            return auth()->guest() ? LoginRedirect::for($request) : null;
        });
    })
    ->create();

$app->useStoragePath($app->basePath('var'));
$app->loadEnvironmentFrom('.env.local');

// The Icms\ library from the web root, which the trust path's composer.json cannot
// name: the web root comes from index.php or, for artisan, from .env.local (design L21).
$app->afterBootstrapping(LoadConfiguration::class, static function (Application $app): void {
    $autoloader = rtrim((string) config('icms.legacy.root'), '/\\') . '/libraries/Autoloader.php';
    if (! is_file($autoloader)) {
        return;
    }

    require_once $autoloader;
});

$app->singleton(HttpKernelContract::class, HostKernel::class);

return $app;
