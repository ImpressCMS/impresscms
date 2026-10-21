<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Legacy\Attribute\RequiresLegacy;
use App\Legacy\LegacyRuntime;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Boots legacy for controllers marked with #[RequiresLegacy].
 */
#[AsEventListener(event: KernelEvents::CONTROLLER)]
final class RequiresLegacyListener
{
    public function __construct(private readonly LegacyRuntime $runtime)
    {
    }

    public function __invoke(ControllerEvent $event): void
    {
        $attributes = $event->getAttributes(RequiresLegacy::class);
        if ($attributes === []) {
            return;
        }

        // the latest stage any attribute asks for
        $stage = null;
        foreach ($attributes as $attribute) {
            /** @var RequiresLegacy $attribute */
            if ($stage === null || array_search($attribute->stage, \Icms\Kernel\Bootstrap::STAGES, true) > array_search($stage, \Icms\Kernel\Bootstrap::STAGES, true)) {
                $stage = $attribute->stage;
            }
        }

        $this->runtime->bootTo((string) $stage);
    }
}
