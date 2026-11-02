<?php

declare(strict_types=1);

namespace App\Legacy;

use Icms\Event;

/**
 * An Icms\Event trigger, dispatched to Laravel listeners as
 * `icms.<namespace>.<name>` (design L9).
 */
final class LegacyEvent
{
    /**
     * @param array<array-key, mixed> $parameters
     */
    public function __construct(
        public readonly string $namespace,
        public readonly string $name,
        public readonly mixed $source,
        public readonly array $parameters,
        public readonly Event $legacyEvent,
    ) {
    }

    public static function eventName(string $namespace, string $name): string
    {
        return "icms.{$namespace}.{$name}";
    }
}
