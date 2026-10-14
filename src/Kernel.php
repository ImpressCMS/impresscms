<?php

declare(strict_types=1);

namespace App;

use App\DependencyInjection\LegacyEventListenersPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * The Symfony host. ImpressCMS runs inside it: requests that no Symfony route
 * claims are executed by the legacy bridge (see public/index.php).
 */
final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function build(ContainerBuilder $container): void
    {
        // after RegisterListenersPass (TYPE_BEFORE_REMOVING, priority 0)
        $container->addCompilerPass(new LegacyEventListenersPass(), PassConfig::TYPE_BEFORE_REMOVING, -100);
    }
}
