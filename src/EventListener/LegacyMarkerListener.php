<?php

declare(strict_types=1);

namespace App\EventListener;

use App\LegacyBridge\LegacyExecutionResponse;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * The marker for a legacy script is not a real response: kernel.response
 * listeners run for the legacy script's response instead (App\Runtime\HostRunner).
 */
#[AsEventListener(event: KernelEvents::RESPONSE, priority: 4096)]
final class LegacyMarkerListener
{
    public function __invoke(ResponseEvent $event): void
    {
        if ($event->getResponse() instanceof LegacyExecutionResponse) {
            $event->stopPropagation();
        }
    }
}
