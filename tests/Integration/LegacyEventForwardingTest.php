<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Legacy\LegacyEventForwarder;
use App\Legacy\LegacyRuntime;
use App\Testing\LoadServiceDbRecorder;
use App\Tests\Support\LegacyAppTestCase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
#[Group('spec:shared-runtime')]
final class LegacyEventForwardingTest extends LegacyAppTestCase
{
    /**
     * @scenario Listening to a legacy event
     */
    public function testLaravelListenerGetsLoadServiceDbOnce(): void
    {
        $legacyCalls = 0;
        \Icms\Event::attach('icms', 'loadService-db', static function () use (&$legacyCalls): void {
            $legacyCalls++;
        });

        $this->app->make(LegacyRuntime::class)->bootTo('module');

        self::assertTrue($this->app->make(LegacyEventForwarder::class)->isInstalled());
        self::assertCount(1, LoadServiceDbRecorder::$events);
        $event = LoadServiceDbRecorder::$events[0];
        self::assertSame('icms', $event->namespace);
        self::assertSame('loadService-db', $event->name);
        self::assertSame('db', $event->parameters['name']);
        self::assertSame(\icms::$db, $event->parameters['service']);
        self::assertSame(1, $legacyCalls, 'legacy listeners are not affected');
    }
}
