<?php

declare(strict_types=1);

namespace App\Testing;

use Illuminate\Foundation\Http\Events\RequestHandled;

/**
 * Test-only: marks every response the host produced, Laravel's and legacy
 * ones run through the bridge, so that Http tests can tell them apart from
 * scripts the web server executed directly. HostKernel dispatches
 * RequestHandled for the legacy response too, before it is sent.
 */
final class HostMarker
{
    public const HEADER = 'X-Test-Icms-Host';

    public function handle(RequestHandled $event): void
    {
        $event->response->headers->set(self::HEADER, '1');
    }
}
