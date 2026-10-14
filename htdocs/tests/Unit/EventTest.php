<?php
/**
 * Icms\Event with and without a forwarder.
 *
 * @package icms\tests
 */

declare(strict_types=1);

namespace Icms\Tests\Unit;

use Icms\Event;
use PHPUnit\Framework\TestCase;

final class EventTest extends TestCase
{
    protected function tearDown(): void
    {
        Event::setForwarder(null);
    }

    public function testWithoutForwarderOnlyRegisteredHandlersRun(): void
    {
        $received = [];
        Event::attach('tests_event_plain', 'fired', static function (array $parameters) use (&$received): void {
            $received[] = $parameters;
        });

        $event = Event::trigger('tests_event_plain', 'fired', null, ['a' => 1]);

        self::assertSame([['a' => 1]], $received);
        self::assertInstanceOf(Event::class, $event);
        self::assertFalse(Event::current(), 'no event is left on the stack');
    }

    public function testForwarderRunsOnceAfterTheHandlers(): void
    {
        $log = [];
        $source = new \stdClass();
        Event::attach('tests_event_forwarded', 'fired', static function () use (&$log): void {
            $log[] = 'handler';
        });
        Event::setForwarder(static function (string $namespace, string $name, mixed $src, array $parameters, Event $event) use (&$log, $source): void {
            $log[] = [$namespace, $name, $src === $source, $parameters, Event::current() === $event];
        });

        Event::trigger('tests_event_forwarded', 'fired', $source, ['b' => 2]);

        self::assertSame(['handler', ['tests_event_forwarded', 'fired', true, ['b' => 2], true]], $log);
    }

    public function testForwarderSeesEventsWithoutHandlers(): void
    {
        $names = [];
        Event::setForwarder(static function (string $namespace, string $name) use (&$names): void {
            $names[] = $namespace . '.' . $name;
        });

        Event::trigger('tests_event_unhandled', 'loadService-db', null);

        self::assertSame(['tests_event_unhandled.loadService-db'], $names);
    }

    public function testRemovedForwarderIsNoLongerCalled(): void
    {
        $calls = 0;
        Event::setForwarder(static function () use (&$calls): void {
            $calls++;
        });
        Event::setForwarder(null);

        Event::trigger('tests_event_removed', 'fired', null);

        self::assertSame(0, $calls);
    }
}
