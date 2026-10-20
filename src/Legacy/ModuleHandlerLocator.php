<?php

declare(strict_types=1);

namespace App\Legacy;

use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Handlers of module classes, such as the IPF handlers of the system module's
 * admin functions (design D10), from icms_getModuleHandler().
 *
 * That function keeps its own per-module cache, apart from icms::handler(), so
 * delegating to it gives the container and legacy code in the same request the
 * very same instance. Core handlers come from App\Legacy\HandlerLocator.
 */
final class ModuleHandlerLocator
{
    public function __construct(private readonly LegacyRuntime $runtime)
    {
    }

    /**
     * @param string $dirname the module directory, for example "system"
     * @param string $name    the handler name, for example "mimetype"
     *
     * @throws ServiceNotFoundException when the module has no such handler
     */
    public function get(string $dirname, string $name): object
    {
        $handler = $this->find($dirname, $name);
        if ($handler === null) {
            throw new ServiceNotFoundException(sprintf('module:%s/%s', $dirname, $name), 'icms.module_handlers', null, [], sprintf('The module "%s" has no handler "%s".', $dirname, $name));
        }

        return $handler;
    }

    public function has(string $dirname, string $name): bool
    {
        return $this->find($dirname, $name) !== null;
    }

    private function find(string $dirname, string $name): ?object
    {
        if (preg_match('/^[a-z0-9_]+$/i', $dirname) !== 1 || preg_match('/^[a-z0-9_]+$/i', $name) !== 1) {
            return null;
        }
        $this->runtime->bootTo('module');
        $handler = icms_getModuleHandler($name, $dirname, null, true);

        return is_object($handler) ? $handler : null;
    }
}
