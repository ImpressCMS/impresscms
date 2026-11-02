<?php

declare(strict_types=1);

namespace App\Http\Controllers\Test;

use App\Legacy\Attributes\IcmsHandler;
use Icms\Data\Page\Handler;
use Illuminate\Http\JsonResponse;

/**
 * Test-only route with an ImpressCMS handler injected into the controller.
 */
final class LegacyHandlerController
{
    public function __invoke(#[IcmsHandler('icms_data_page')] Handler $pages): JsonResponse
    {
        return new JsonResponse([
            'class' => $pages::class,
            'same_as_legacy' => $pages === \icms::handler('icms_data_page'),
        ]);
    }
}
