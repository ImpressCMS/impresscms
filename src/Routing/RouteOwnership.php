<?php

declare(strict_types=1);

namespace App\Routing;

use App\LegacyBridge\LegacyBridge;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Routing\Exception\ExceptionInterface as RoutingException;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Which application owns a request.
 *
 * Symfony routes and legacy ImpressCMS share one URL space: the legacy bridge
 * is a catch-all route with the lowest priority, so the router itself decides
 * (design D4). Because the catch-all accepts every method, the router never
 * reports 405 on its own; allowedMethods() finds the methods of the Symfony
 * routes that match the path, so the bridge can answer 405 instead of running
 * legacy code.
 */
final class RouteOwnership
{
    public const LEGACY_ROUTE = 'icms_legacy';
    public const RESERVED_ROUTE_PREFIX = 'icms_reserved_';

    private const PROBE_METHODS = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];

    public function __construct(
        #[Autowire(service: 'router')]
        private readonly RouterInterface&RequestMatcherInterface $router,
        private readonly LegacyBridge $bridge,
    ) {
    }

    public static function isLegacyRoute(string $route): bool
    {
        return $route === self::LEGACY_ROUTE;
    }

    /**
     * Methods accepted by Symfony routes (other than the legacy catch-all) for
     * the request's path and query; empty when no Symfony route matches them.
     *
     * @return list<string>
     */
    public function allowedMethods(Request $request): array
    {
        $allowed = [];
        foreach (self::PROBE_METHODS as $method) {
            // The matcher takes the method from the request it is given.
            $probe = $request->duplicate();
            $probe->setMethod($method);
            try {
                $route = (string) ($this->router->matchRequest($probe)['_route'] ?? '');
            } catch (RoutingException) {
                continue;
            }
            if (!self::isLegacyRoute($route)) {
                $allowed[] = $method;
            }
        }

        if (in_array('GET', $allowed, true)) {
            array_splice($allowed, (int) array_search('GET', $allowed, true) + 1, 0, ['HEAD']);
        }

        return $allowed;
    }

    /**
     * Describe the owner of a request, for `icms:routes:ownership`.
     *
     * @return array{owner: string, route: ?string, detail: string}
     */
    public function describe(Request $request): array
    {
        try {
            $route = (string) ($this->router->matchRequest($request)['_route'] ?? '');
        } catch (RoutingException $e) {
            return ['owner' => 'symfony', 'route' => null, 'detail' => 'no route matches (' . $e::class . ')'];
        }

        if (str_starts_with($route, self::RESERVED_ROUTE_PREFIX)) {
            return ['owner' => 'symfony', 'route' => $route, 'detail' => 'reserved prefix: 404, never legacy'];
        }
        if (!self::isLegacyRoute($route)) {
            return ['owner' => 'symfony', 'route' => $route, 'detail' => 'Symfony route'];
        }

        $allowed = $this->allowedMethods($request);
        if ($allowed !== []) {
            return ['owner' => 'symfony', 'route' => null, 'detail' => '405 Method Not Allowed (Allow: ' . implode(', ', $allowed) . ')'];
        }

        try {
            $resolution = $this->bridge->resolve($request);
        } catch (HttpExceptionInterface $e) {
            return ['owner' => 'legacy', 'route' => $route, 'detail' => $e->getStatusCode() . ': ' . $e->getMessage()];
        }

        return ['owner' => 'legacy', 'route' => $route, 'detail' => $resolution->scriptName . ' (' . $resolution->strategy . ')'];
    }
}
