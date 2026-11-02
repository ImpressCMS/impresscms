<?php

declare(strict_types=1);

namespace App\Http\Controllers\Test;

use Illuminate\Http\Response;

/**
 * Test-only route that reads ImpressCMS configuration from a Laravel
 * controller (`icms.legacy:siteState`).
 */
final class LegacyConfigurationController
{
    public function __invoke(): Response
    {
        return new Response((string) $GLOBALS['icmsConfig']['sitename'], Response::HTTP_OK, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
