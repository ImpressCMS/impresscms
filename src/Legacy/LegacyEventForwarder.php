<?php

declare(strict_types=1);

namespace App\Legacy;

use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Forwards Icms\Event triggers to the Symfony event dispatcher (design D9).
 *
 * Only installed when Symfony listeners for `icms.*` events exist (collected
 * while the container is compiled, see LegacyEventListenersPass), and only
 * those events are dispatched. Legacy handlers and preloads run first and are
 * not affected.
 */
final class LegacyEventForwarder
{
    /** @var array<string, true> */
    private readonly array $eventNames;

    private bool $installed = false;

    /**
     * @param list<string> $eventNames `icms.*` events that have Symfony listeners
     */
    public function __construct(
        private readonly EventDispatcherInterface $dispatcher,
        array $eventNames = [],
    ) {
        $this->eventNames = array_fill_keys($eventNames, true);
    }

    public function install(): void
    {
        if ($this->installed || $this->eventNames === []) {
            return;
        }
        $this->installed = true;

        \Icms\Event::setForwarder(function (string $namespace, string $name, mixed $source, array $parameters, \Icms\Event $event): void {
            $eventName = LegacyEvent::eventName($namespace, $name);
            if (isset($this->eventNames[$eventName])) {
                $this->dispatcher->dispatch(new LegacyEvent($namespace, $name, $source, $parameters, $event), $eventName);
            }
        });
    }

    public function isInstalled(): bool
    {
        return $this->installed;
    }
}
