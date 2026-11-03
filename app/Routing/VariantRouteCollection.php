<?php

declare(strict_types=1);

namespace App\Routing;

use Illuminate\Routing\RouteCollection;

/**
 * Laravel's route collection, keeping every route of a URL.
 *
 * RouteCollection keys routes by method, domain and URI, so a second route
 * for the same method and URI replaces the first. Takeovers of legacy URLs
 * share URIs: every system admin function is `/modules/system/admin.php`,
 * told apart by `whereQuery()`/`whereInput()` (design L26). Here a route
 * whose key is taken gets a key of its own, after the existing ones, so the
 * router tries them in the order they were registered.
 */
final class VariantRouteCollection extends RouteCollection
{
    protected function addToCollections($route): void
    {
        $domainAndUri = $route->getDomain() . $route->uri();
        $variant = spl_object_id($route);

        foreach ($route->methods() as $method) {
            $key = $this->freeKey($this->routes[$method] ?? [], $domainAndUri, $route, $variant);
            $this->routes[$method][$key] = $route;
        }

        $all = implode('|', $route->methods()) . $domainAndUri;
        $this->allRoutes[$this->freeKey($this->allRoutes, $all, $route, $variant)] = $route;
    }

    /**
     * @param array<string, mixed> $routes
     */
    private function freeKey(array $routes, string $key, object $route, int $variant): string
    {
        if (! isset($routes[$key]) || $routes[$key] === $route) {
            return $key;
        }

        return "{$key}#{$variant}";
    }
}
