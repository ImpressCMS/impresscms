<?php

declare(strict_types=1);

namespace App\EventListener\Test;

use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Test-only: marks every response the host produced, Symfony's and legacy
 * ones run through the bridge, so that Http tests can tell them apart from
 * scripts the web server executed directly.
 */
#[When(env: 'test')]
#[AsEventListener(event: KernelEvents::RESPONSE)]
final class HostMarkerListener
{
    public const HEADER = 'X-Test-Icms-Host';

    public function __invoke(ResponseEvent $event): void
    {
        if ($event->isMainRequest()) {
            $event->getResponse()->headers->set(self::HEADER, '1');
        }
    }
}
