<?php

declare(strict_types=1);

namespace App\Providers;

use App\LegacyBridge\LegacyBridge;
use App\LegacyBridge\LegacyRequestHydrator;
use App\LegacyBridge\LegacyScriptResolver;
use App\Routing\RequestConditionValidator;
use App\Routing\Takeovers;
use App\Routing\VariantRouteCollection;
use App\Testing\TestServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;
use LogicException;
use Psr\Log\LoggerInterface;

/**
 * The legacy bridge and the routing extensions it needs (design L4, L5).
 */
final class HostServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(LegacyScriptResolver::class, static fn (Application $app): LegacyScriptResolver => new LegacyScriptResolver(
            (string) config('icms.legacy.root'),
            (string) config('icms.legacy.front_controller'),
            config('icms.legacy.asset_extensions'),
            config('icms.legacy.non_public_paths'),
            config('icms.legacy.script_free_paths'),
        ));

        $this->app->singleton(LegacyBridge::class, static fn (Application $app): LegacyBridge => new LegacyBridge(
            $app->make(LegacyScriptResolver::class),
            new LegacyRequestHydrator(),
            config('icms.legacy.mime_types'),
            (bool) config('app.debug'),
            $app->make(LoggerInterface::class),
            $app->basePath('host.php'),
        ));

        $this->app->singleton(Takeovers::class);

        // before any route is added: takeovers share URIs (VariantRouteCollection)
        $this->app->make('router')->setRoutes(new VariantRouteCollection());
        $this->registerRouteConditions();

        if ($this->app->environment('test')) {
            $this->app->register(TestServiceProvider::class);
        }
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        if ($this->app->routesAreCached()) {
            throw new LogicException('Cached routes are not supported: the compiled matcher ignores whereQuery() and matching(). Run `php artisan route:clear`.');
        }
    }

    /**
     * Route::whereQuery(['fct' => 'users']), Route::whereInput(['fct' => 'mimetype']),
     * Route::matching(Condition::class) and Route::takeover('system.mimetype'),
     * evaluated by RequestConditionValidator.
     */
    private function registerRouteConditions(): void
    {
        // static: replaced, so a later application (each test) does not run with an earlier container
        $others = array_filter(Route::getValidators(), static fn (object $validator): bool => ! $validator instanceof RequestConditionValidator);
        Route::$validators = [...$others, new RequestConditionValidator($this->app)];

        Route::macro('whereQuery', function (array $parameters): Route {
            /** @var Route $this */
            $this->action[RequestConditionValidator::QUERY] = $parameters + ($this->action[RequestConditionValidator::QUERY] ?? []);

            return $this;
        });

        Route::macro('whereInput', function (array $parameters): Route {
            /** @var Route $this */
            $this->action[RequestConditionValidator::INPUT] = $parameters + ($this->action[RequestConditionValidator::INPUT] ?? []);

            return $this;
        });

        Route::macro('matching', function (string $condition): Route {
            /** @var Route $this */
            $this->action[RequestConditionValidator::CONDITIONS][] = $condition;

            return $this;
        });

        Route::macro('takeover', function (string $key): Route {
            /** @var Route $this */
            $this->action[RequestConditionValidator::TAKEOVER] = $key;

            return $this;
        });
    }
}
