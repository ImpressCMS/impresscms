<?php

declare(strict_types=1);

namespace App\Controller\Test;

use App\Legacy\Attribute\IcmsHandler;
use App\Legacy\Attribute\RequiresLegacy;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Test-only route with an ImpressCMS handler injected into the controller.
 */
final class LegacyHandlerController
{
    #[Route('/_test/legacy/handler', name: 'test_legacy_handler', methods: ['GET'], env: 'test')]
    #[RequiresLegacy(stage: 'module')]
    public function __invoke(#[IcmsHandler('icms_data_page')] \Icms\Data\Page\Handler $pages): JsonResponse
    {
        return new JsonResponse([
            'class' => $pages::class,
            'same_as_legacy' => $pages === \icms::handler('icms_data_page'),
        ]);
    }
}
