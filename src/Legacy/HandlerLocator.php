<?php

declare(strict_types=1);

namespace App\Legacy;

use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Contracts\Service\ServiceProviderInterface;

/**
 * ImpressCMS handlers as a service locator (`icms.handlers`, design D8).
 *
 * Delegates to icms::handler(), so the container and legacy code get the very
 * same instance. Handlers are found by their legacy name ("icms_member",
 * "icms_data_page") or by their PSR-4 class (Icms\Data\Page\Handler::class);
 * both resolve to the same icms::handler() entry. Module handlers use
 * "module:<dirname>/<name>" and delegate to icms_getModuleHandler().
 *
 * @implements ServiceProviderInterface<object>
 */
final class HandlerLocator implements ServiceProviderInterface
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
        $handler = $this->find($id);
        if ($handler === null) {
            throw new ServiceNotFoundException($id, 'icms.handlers', null, [], sprintf('ImpressCMS has no handler "%s".', $id));
        }

        return $handler;
    }

    public function has(string $id): bool
    {
        return $this->find($id) !== null;
    }

    /**
     * @return array<string, string>
     */
    public function getProvidedServices(): array
    {
        // Handlers are discovered by name at runtime (installed modules vary).
        return [];
    }

    /**
     * The icms::handler() name for an id: the legacy name itself, or the legacy
     * name of a PSR-4 handler class.
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
            [$module, $name] = array_pad(explode('/', substr($id, 7), 2), 2, null);
            if ($module === null || $module === '' || $name === null || $name === '') {
                return null;
            }
            $handler = icms_getModuleHandler($name, $module, null, true);

            return is_object($handler) ? $handler : null;
        }

        $handler = \icms::handler($this->legacyName($id), true);

        return is_object($handler) ? $handler : null;
    }
}
