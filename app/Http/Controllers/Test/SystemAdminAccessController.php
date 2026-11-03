<?php

declare(strict_types=1);

namespace App\Http\Controllers\Test;

use App\Admin\SystemAdminAccess;
use Illuminate\Http\Response;

/**
 * Test-only: whether the current user may use a system admin function
 * (App\Admin\SystemAdminAccess), without entering it.
 */
final class SystemAdminAccessController
{
    public function __invoke(string $fct, SystemAdminAccess $access): Response
    {
        $granted = $access->isGranted($fct, $access->currentGroups());

        return new Response($granted ? 'granted' : 'denied', Response::HTTP_OK, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
