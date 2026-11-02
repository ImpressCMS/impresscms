<?php

declare(strict_types=1);

namespace App\Legacy;

use Illuminate\Container\EntryNotFoundException;
use Psr\Container\ContainerInterface;

/**
 * ImpressCMS handlers in the container (`icms.handlers`, design L8).
 *
 * Delegates to icms::handler($name, true), so the instance is the one legacy
 * code gets in the same request. An id is a legacy handler name
 * (`icms_member`), the PSR-4 class of a core handler (`Icms\Member\Handler`),
 * or `module:<dirname>/<name>` for an IPF module handler
 * (icms_getModuleHandler(), which keeps its own cache).
 */
final class HandlerLocator implements ContainerInterface
{
    /** @var array<string, string>|null lower-case PSR-4 handler class => legacy handler name */
    private ?array $legacyNames = null;

    public function __construct(
        private readonly LegacyRuntime $runtime,
        private readonly string $aliasMap,
    ) {
    }

    public function get(string $id): object
    {
        return $this->find($id) ?? throw new EntryNotFoundException("ImpressCMS has no handler \"{$id}\".");
    }

    public function has(string $id): bool
    {
        return $this->find($id) !== null;
    }

    /**
     * The icms::handler() name for an id: the legacy name itself, or the
     * legacy name of a PSR-4 handler class.
     */
    public function legacyName(string $id): string
    {
        $id = ltrim($id, '\\');

        if ($this->legacyNames === null) {
            $this->legacyNames = [];
            foreach (require $this->aliasMap as $legacy => $modern) {
                if (str_ends_with(strtolower($legacy), '_handler')) {
                    $this->legacyNames[strtolower($modern)] = substr($legacy, 0, -strlen('_Handler'));
                }
            }
        }

        return $this->legacyNames[strtolower($id)] ?? $id;
    }

    private function find(string $id): ?object
    {
        $this->runtime->bootTo('module');

        if (str_starts_with($id, 'module:')) {
            return $this->findModuleHandler(substr($id, strlen('module:')));
        }

        $handler = \icms::handler($this->legacyName($id), true);

        return is_object($handler) ? $handler : null;
    }

    private function findModuleHandler(string $path): ?object
    {
        [$module, $name] = array_pad(explode('/', $path, 2), 2, '');
        if ($module === '' || $name === '') {
            return null;
        }

        $handler = icms_getModuleHandler($name, $module, null, true);

        return is_object($handler) ? $handler : null;
    }
}
