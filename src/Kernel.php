<?php

declare(strict_types=1);

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * The Symfony host. ImpressCMS runs inside it: requests that no Symfony route
 * claims are executed by the legacy bridge (see public/index.php).
 */
final class Kernel extends BaseKernel
{
    use MicroKernelTrait;
}
