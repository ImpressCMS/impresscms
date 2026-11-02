<?php

declare(strict_types=1);

namespace App\Legacy;

use Icms\Event;
use Illuminate\Contracts\Events\Dispatcher;

/**
 * Forwards every Icms\Event trigger that has a Laravel listener to the
 * Laravel dispatcher (design L9). Laravel registers listeners at runtime, so
 * the check runs per event: a lookup in the dispatcher, including wildcard
 * listeners such as `icms.*`.
 */
final class LegacyEventForwarder
{
    private bool $installed = false;

    public function __construct(private readonly Dispatcher $dispatcher)
    {
    }

    public function install(): void
    {
        if ($this->installed) {
            return;
        }

        $this->installed = true;

        Event::setForwarder(function (string $namespace, string $name, mixed $source, array $parameters, Event $event): void {
            $eventName = LegacyEvent::eventName($namespace, $name);
            if (! $this->dispatcher->hasListeners($eventName)) {
                return;
            }

            $this->dispatcher->dispatch($eventName, [new LegacyEvent($namespace, $name, $source, $parameters, $event)]);
        });
    }

    public function isInstalled(): bool
    {
        return $this->installed;
    }
}
