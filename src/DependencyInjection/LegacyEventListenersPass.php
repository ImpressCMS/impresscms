<?php

declare(strict_types=1);

namespace App\DependencyInjection;

use App\Legacy\LegacyEventForwarder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Tells LegacyEventForwarder which `icms.*` events have Symfony listeners.
 * Runs after RegisterListenersPass, which turns listener attributes, tags and
 * subscribers into addListener() calls on the event dispatcher.
 */
final class LegacyEventListenersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(LegacyEventForwarder::class)
            || (!$container->hasDefinition('event_dispatcher') && !$container->hasAlias('event_dispatcher'))) {
            return;
        }

        $events = [];
        foreach ($container->findDefinition('event_dispatcher')->getMethodCalls() as [$method, $arguments]) {
            $event = $arguments[0] ?? null;
            if ($method === 'addListener' && is_string($event) && str_starts_with($event, 'icms.')) {
                $events[$event] = true;
            }
        }

        // $eventNames, by position: named arguments are already resolved at this point
        $container->getDefinition(LegacyEventForwarder::class)->setArgument(1, array_keys($events));
    }
}
