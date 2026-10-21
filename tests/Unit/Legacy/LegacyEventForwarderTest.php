<?php

declare(strict_types=1);

namespace App\Tests\Unit\Legacy;

use App\Legacy\LegacyEvent;
use App\Legacy\LegacyEventForwarder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

final class LegacyEventForwarderTest extends TestCase
{
    protected function tearDown(): void
    {
        \Icms\Event::setForwarder(null);
    }

    public function testWithoutListenersNothingIsInstalled(): void
    {
        $forwarder = new LegacyEventForwarder(new EventDispatcher(), []);
        $forwarder->install();

        self::assertFalse($forwarder->isInstalled());
    }

    public function testOnlyEventsWithListenersAreDispatched(): void
    {
        $dispatcher = new EventDispatcher();
        $received = [];
        $dispatcher->addListener('icms.tests_forwarder.wanted', static function (LegacyEvent $event) use (&$received): void {
            $received[] = $event->name;
        });
        $dispatcher->addListener('icms.tests_forwarder.unlisted', static function () use (&$received): void {
            $received[] = 'unlisted';
        });
        $forwarder = new LegacyEventForwarder($dispatcher, ['icms.tests_forwarder.wanted']);
        $forwarder->install();
        $forwarder->install();

        \Icms\Event::trigger('tests_forwarder', 'wanted', null, ['x' => 1]);
        \Icms\Event::trigger('tests_forwarder', 'unlisted', null);

        self::assertSame(['wanted'], $received);
    }
}
