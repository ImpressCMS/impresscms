<?php

declare(strict_types=1);

namespace App\Controller\Test;

use App\Legacy\Attribute\RequiresLegacy;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Test-only route that reads ImpressCMS configuration from a Symfony controller.
 */
final class LegacyConfigurationController
{
    #[Route('/_test/legacy/sitename', name: 'test_legacy_sitename', methods: ['GET'], env: 'test')]
    #[RequiresLegacy(stage: 'siteState')]
    public function __invoke(): Response
    {
        return new Response((string) $GLOBALS['icmsConfig']['sitename'], Response::HTTP_OK, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
