<?php

declare(strict_types=1);

namespace App\Routing;

use App\Controller\ReservedPrefixController;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Routes for reserved prefixes (icms.legacy.reserved_prefixes): areas that
 * belong to Symfony and must never fall through to legacy, even when no
 * concrete route matches. They answer 404 and rank just above the legacy
 * catch-all. None are configured by default.
 */
final class ReservedPrefixLoader extends Loader
{
    public const TYPE = 'icms_reserved';
    private const PRIORITY = -900;

    private bool $loaded = false;

    /**
     * @param list<string> $prefixes paths or prefixes such as "admin-next"
     */
    public function __construct(private readonly array $prefixes, ?string $env = null)
    {
        parent::__construct($env);
    }

    public function load(mixed $resource, ?string $type = null): RouteCollection
    {
        if ($this->loaded) {
            throw new \RuntimeException('The reserved prefix routes are already loaded.');
        }
        $this->loaded = true;

        $routes = new RouteCollection();
        foreach ($this->prefixes as $prefix) {
            $prefix = trim($prefix, '/');
            if ($prefix === '') {
                continue;
            }
            $name = RouteOwnership::RESERVED_ROUTE_PREFIX . preg_replace('/[^a-z0-9]+/i', '_', $prefix);
            $defaults = ['_controller' => ReservedPrefixController::class];
            $routes->add($name, new Route('/' . $prefix, $defaults), self::PRIORITY);
            $routes->add($name . '_any', new Route('/' . $prefix . '/{rest}', $defaults, ['rest' => '.*']), self::PRIORITY);
        }

        return $routes;
    }

    public function supports(mixed $resource, ?string $type = null): bool
    {
        return $type === self::TYPE;
    }
}
