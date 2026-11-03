<?php

declare(strict_types=1);

namespace App\Routing;

use App\LegacyBridge\LegacyBridge;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Which application owns a request.
 *
 * Laravel routes and legacy ImpressCMS share one URL space: the legacy bridge
 * is an all-method route marked as fallback, so the router itself decides
 * (design L4). Because that route accepts every method, the router never
 * answers 405 on its own; allowedMethods() finds the methods of the other
 * routes that match the path, so the bridge can answer 405 instead of running
 * legacy code.
 */
final class RouteOwnership
{
    public const LEGACY_ROUTE = 'icms.legacy';
    public const RESERVED_ROUTE_PREFIX = 'icms.reserved.';

    private const PROBE_METHODS = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];

    public function __construct(
        private readonly Router $router,
        private readonly LegacyBridge $bridge,
        private readonly Takeovers $takeovers,
    ) {
    }

    /**
     * Methods accepted by Laravel routes (other than the legacy fallback) for
     * the request's path and query; empty when no Laravel route matches them.
     *
     * @return list<string>
     */
    public function allowedMethods(Request $request): array
    {
        $allowed = [];
        foreach (self::PROBE_METHODS as $method) {
            $probe = $request->duplicate();
            $probe->setMethod($method);

            try {
                $route = $this->router->getRoutes()->match($probe);
            } catch (HttpExceptionInterface) {
                continue;
            }

            if ($route->getName() !== self::LEGACY_ROUTE) {
                $allowed[] = $method;
            }
        }

        if (in_array('GET', $allowed, true)) {
            array_splice($allowed, (int) array_search('GET', $allowed, true) + 1, 0, ['HEAD']);
        }

        return $allowed;
    }

    /**
     * The owner of a request, for `icms:routes:ownership`.
     *
     * @return array{
     *     owner: string,
     *     route: ?string,
     *     detail: string
     * }
     */
    public function describe(Request $request): array
    {
        try {
            $route = (string) $this->router->getRoutes()->match($request)->getName();
        } catch (HttpExceptionInterface $e) {
            return ['owner' => 'laravel', 'route' => null, 'detail' => "no route matches ({$e->getStatusCode()})"];
        }

        if (str_starts_with($route, self::RESERVED_ROUTE_PREFIX)) {
            return ['owner' => 'laravel', 'route' => $route, 'detail' => 'reserved prefix: 404, never legacy'];
        }

        if ($route !== self::LEGACY_ROUTE) {
            return ['owner' => 'laravel', 'route' => $route, 'detail' => 'Laravel route'];
        }

        $allowed = $this->allowedMethods($request);
        if ($allowed !== []) {
            $methods = implode(', ', $allowed);

            return ['owner' => 'laravel', 'route' => null, 'detail' => "405 Method Not Allowed (Allow: {$methods})"];
        }

        try {
            $resolution = $this->bridge->resolve($request);
        } catch (HttpExceptionInterface $e) {
            return ['owner' => 'legacy', 'route' => $route, 'detail' => "{$e->getStatusCode()}: {$e->getMessage()}"];
        }

        $detail = "{$resolution->scriptName} ({$resolution->strategy})";
        $disabled = $this->disabledTakeover($request);
        if ($disabled !== null) {
            $detail = "{$detail}; takeover {$disabled['key']} disabled (route {$disabled['route']})";
        }

        return ['owner' => 'legacy', 'route' => $route, 'detail' => $detail];
    }

    /**
     * The switched-off takeover that would serve a legacy-owned request.
     *
     * @return array{
     *     key: string,
     *     route: string
     * }|null
     */
    private function disabledTakeover(Request $request): ?array
    {
        $route = $this->takeovers->ignoringSwitch(fn () => $this->router->getRoutes()->match($request));
        $key = $route->getAction(RequestConditionValidator::TAKEOVER);
        if (! is_string($key)) {
            return null;
        }

        return ['key' => $key, 'route' => (string) $route->getName()];
    }
}
