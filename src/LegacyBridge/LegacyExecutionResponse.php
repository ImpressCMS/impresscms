<?php

declare(strict_types=1);

namespace App\LegacyBridge;

use Symfony\Component\HttpFoundation\Response;

/**
 * Marker returned by the legacy bridge controller for PHP scripts.
 *
 * Legacy scripts rely on top-level variables being globals, so they cannot run
 * inside a controller. public/index.php sees this marker after the kernel has
 * handled the request and executes the script at file scope (design D5).
 */
final class LegacyExecutionResponse extends Response
{
    public function __construct(public readonly LegacyResolution $resolution)
    {
        parent::__construct();
    }
}
