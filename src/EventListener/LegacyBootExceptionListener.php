<?php

declare(strict_types=1);

namespace App\EventListener;

use Icms\Kernel\NotInstalledException;
use Icms\Kernel\SiteClosedException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Turns the conditions that stop a headless legacy boot into responses:
 * a closed site is 503, a missing installation redirects to the installer.
 */
#[AsEventListener(event: KernelEvents::EXCEPTION, priority: 64)]
final class LegacyBootExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        for ($exception = $event->getThrowable(); $exception !== null; $exception = $exception->getPrevious()) {
            if ($exception instanceof SiteClosedException) {
                $event->setResponse(new Response(
                    "<!DOCTYPE html>\n<html><head><meta charset=\"UTF-8\"><title>503 Service Unavailable</title></head>"
                    . "<body><h1>Service Unavailable</h1><p>The site is closed for maintenance.</p></body></html>\n",
                    Response::HTTP_SERVICE_UNAVAILABLE,
                    ['Content-Type' => 'text/html; charset=UTF-8', 'Retry-After' => '3600'],
                ));

                return;
            }
            if ($exception instanceof NotInstalledException) {
                $event->setResponse(new RedirectResponse($event->getRequest()->getBasePath() . '/install/'));

                return;
            }
        }
    }
}
