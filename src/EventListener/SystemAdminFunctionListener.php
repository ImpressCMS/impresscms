<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Admin\Attribute\SystemAdminFunction;
use App\Admin\SystemAdminContext;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Sets up the admin context for controllers marked #[SystemAdminFunction]
 * (design D3). Runs before RequiresLegacyListener, so that ImpressCMS boots
 * in admin mode. A denied visitor gets the legacy denial instead of the
 * controller.
 */
final class SystemAdminFunctionListener
{
    public function __construct(private readonly SystemAdminContext $context)
    {
    }

    #[AsEventListener(event: KernelEvents::CONTROLLER, priority: 20)]
    public function onController(ControllerEvent $event): void
    {
        $attributes = $event->getAttributes(SystemAdminFunction::class);
        if ($attributes === [] || !$event->isMainRequest()) {
            return;
        }
        /** @var SystemAdminFunction $attribute */
        $attribute = end($attributes);

        $denial = $this->context->enter($attribute->fct, $event->getRequest());
        if ($denial !== null) {
            $event->setController(static fn () => $denial);
        }
    }

    #[AsEventListener(event: KernelEvents::FINISH_REQUEST)]
    public function onFinishRequest(FinishRequestEvent $event): void
    {
        if ($event->isMainRequest()) {
            $this->context->leave();
        }
    }
}
