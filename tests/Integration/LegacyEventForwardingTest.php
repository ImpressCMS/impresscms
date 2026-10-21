<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\EventListener\Test\LoadServiceDbRecorder;
use App\Legacy\LegacyEventForwarder;
use App\Legacy\LegacyRuntime;
use App\Tests\Support\LegacyWebTestCase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
#[Group('spec:shared-runtime')]
final class LegacyEventForwardingTest extends LegacyWebTestCase
{
    /**
     * @scenario Listening to a legacy event
     */
    public function testSymfonyListenerGetsLoadServiceDbOnce(): void
    {
        self::bootKernel();
        $legacyCalls = 0;
        \Icms\Event::attach('icms', 'loadService-db', static function () use (&$legacyCalls): void {
            $legacyCalls++;
        });

        static::getContainer()->get(LegacyRuntime::class)->bootTo('module');

        self::assertTrue(static::getContainer()->get(LegacyEventForwarder::class)->isInstalled());
        self::assertCount(1, LoadServiceDbRecorder::$events);
        $event = LoadServiceDbRecorder::$events[0];
        self::assertSame('icms', $event->namespace);
        self::assertSame('loadService-db', $event->name);
        self::assertSame('db', $event->parameters['name']);
        self::assertSame(\icms::$db, $event->parameters['service']);
        self::assertSame(1, $legacyCalls, 'legacy listeners are not affected');
    }
}
