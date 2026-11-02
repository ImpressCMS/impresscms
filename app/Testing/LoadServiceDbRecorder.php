<?php

declare(strict_types=1);

namespace App\Testing;

use App\Legacy\LegacyEvent;

/**
 * Test-only listener on `icms.icms.loadService-db`: records the event and
 * reports it in a response header for the Http suite.
 */
final class LoadServiceDbRecorder
{
    /** @var list<LegacyEvent> */
    public static array $events = [];

    public function handle(LegacyEvent $event): void
    {
        self::$events[] = $event;

        if (headers_sent()) {
            return;
        }

        header('X-Test-Laravel-LoadService-db: ' . get_debug_type($event->parameters['service'] ?? null), false);
    }
}
