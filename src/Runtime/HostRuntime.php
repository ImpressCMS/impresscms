<?php

declare(strict_types=1);

namespace App\Runtime;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Runtime\RunnerInterface;
use Symfony\Component\Runtime\SymfonyRuntime;

/**
 * SymfonyRuntime whose HTTP runner hands legacy scripts back to
 * host.php instead of running them inside the kernel.
 */
final class HostRuntime extends SymfonyRuntime
{
    private bool $debug;

    /**
     * @param array<string, mixed> $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);
        $this->debug = (bool) ($options['debug'] ?? $_SERVER['APP_DEBUG'] ?? false);
    }

    public function getRunner(?object $application): RunnerInterface
    {
        if ($application instanceof HttpKernelInterface) {
            return new HostRunner($application, Request::createFromGlobals(), $this->debug);
        }

        return parent::getRunner($application);
    }
}
