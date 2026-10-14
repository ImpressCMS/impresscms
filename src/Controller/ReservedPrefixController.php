<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Target of the reserved prefix routes: nothing here, and never legacy.
 */
final class ReservedPrefixController
{
    public function __invoke(): never
    {
        throw new NotFoundHttpException('No route under a reserved prefix matches.');
    }
}
