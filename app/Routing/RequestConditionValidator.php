<?php

declare(strict_types=1);

namespace App\Routing;

use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Routing\Matching\ValidatorInterface;
use Illuminate\Routing\Route;

/**
 * Route validator for the conditions that Laravel routes cannot express:
 * required query parameters (`whereQuery()`), parameters from the query or
 * else the POST body (`whereInput()`), RouteCondition classes (`matching()`)
 * and switchable takeovers (`takeover()`). They are stored as plain data in
 * the route action.
 *
 * Laravel's compiled route matcher (`route:cache`) does not run route
 * validators, so the host refuses cached routes (HostServiceProvider).
 */
final class RequestConditionValidator implements ValidatorInterface
{
    public const QUERY = 'icms_query';
    public const INPUT = 'icms_input';
    public const CONDITIONS = 'icms_conditions';
    public const TAKEOVER = 'icms_takeover';

    public function __construct(private readonly Container $container)
    {
    }

    public function matches(Route $route, Request $request): bool
    {
        $query = $request->query->all();
        foreach ((array) $route->getAction(self::QUERY) as $name => $value) {
            if (($query[$name] ?? null) !== (string) $value) {
                return false;
            }
        }

        // as modules/system/admin.php reads `fct`: the query string, else the body
        $body = $request->request->all();
        foreach ((array) $route->getAction(self::INPUT) as $name => $value) {
            if (($query[$name] ?? $body[$name] ?? null) !== (string) $value) {
                return false;
            }
        }

        $takeover = $route->getAction(self::TAKEOVER);
        if (is_string($takeover) && ! $this->container->make(Takeovers::class)->enabled($takeover)) {
            return false;
        }

        foreach ((array) $route->getAction(self::CONDITIONS) as $condition) {
            /** @var RouteCondition $instance */
            $instance = $this->container->make($condition);
            if (! $instance->matches($request)) {
                return false;
            }
        }

        return true;
    }
}
