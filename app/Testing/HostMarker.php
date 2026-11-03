<?php

declare(strict_types=1);

namespace App\Testing;

use Illuminate\Foundation\Http\Events\RequestHandled;

/**
 * Test-only: marks every response the host produced, Laravel's and legacy
 * ones run through the bridge, so that Http tests can tell them apart from
 * scripts the web server executed directly. HostKernel dispatches
 * RequestHandled for the legacy response too, before it is sent. The route
 * that answered is in a second header (`icms.legacy` for legacy scripts).
 */
final class HostMarker
{
    public const HEADER = 'X-Test-Icms-Host';
    public const ROUTE_HEADER = 'X-Test-Icms-Route';

    public function handle(RequestHandled $event): void
    {
        $event->response->headers->set(self::HEADER, '1');
        $event->response->headers->set(self::ROUTE_HEADER, (string) $event->request->route()?->getName());
    }
}
