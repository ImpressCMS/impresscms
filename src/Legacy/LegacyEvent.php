<?php

declare(strict_types=1);

namespace App\Legacy;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * An Icms\Event, dispatched to Symfony listeners as `icms.<namespace>.<name>`,
 * for example `icms.icms.loadService-db` (design D9).
 */
final class LegacyEvent extends Event
{
    /**
     * @param array<array-key, mixed> $parameters
     */
    public function __construct(
        public readonly string $namespace,
        public readonly string $name,
        public readonly mixed $source,
        public readonly array $parameters,
        public readonly \Icms\Event $legacyEvent,
    ) {
    }

    public static function eventName(string $namespace, string $name): string
    {
        return 'icms.' . $namespace . '.' . $name;
    }
}
