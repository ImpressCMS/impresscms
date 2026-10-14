<?php

declare(strict_types=1);

namespace App\EventListener\Test;

use App\Legacy\LegacyEvent;
use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 * Test-only listener for the legacy `loadService-db` event; adds a response
 * header for each call so that Http tests can see it too.
 */
#[When(env: 'test')]
#[AsEventListener(event: 'icms.icms.loadService-db')]
final class LoadServiceDbRecorder
{
    /** @var list<LegacyEvent> */
    public static array $events = [];

    public function __invoke(LegacyEvent $event): void
    {
        self::$events[] = $event;
        if (!headers_sent()) {
            header('X-Test-Symfony-LoadService-db: ' . get_debug_type($event->parameters['service'] ?? null), false);
        }
    }
}
