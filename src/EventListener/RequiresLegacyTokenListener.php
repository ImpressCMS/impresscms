<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Legacy\LegacyRuntime;
use App\Security\Attribute\RequiresLegacyToken;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Validates the ImpressCMS security token for #[RequiresLegacyToken] on POST.
 */
#[AsEventListener(event: KernelEvents::CONTROLLER, priority: -10)]
final class RequiresLegacyTokenListener
{
    public function __construct(private readonly LegacyRuntime $runtime)
    {
    }

    public function __invoke(ControllerEvent $event): void
    {
        if (!$event->getRequest()->isMethod('POST') || $event->getAttributes(RequiresLegacyToken::class) === []) {
            return;
        }

        // the token names and messages are defined by the core language files
        $this->runtime->bootTo('language');
        if (!\icms::$security->check()) {
            throw new AccessDeniedHttpException('Invalid or expired ImpressCMS security token.');
        }
    }
}
