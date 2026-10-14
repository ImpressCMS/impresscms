<?php

declare(strict_types=1);

namespace App\Controller;

use App\LegacyBridge\LegacyBridge;
use App\LegacyBridge\LegacyExecutionResponse;
use App\Routing\RouteOwnership;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Lowest-priority catch-all: every request that no Symfony route claims is
 * legacy ImpressCMS. Assets are served here; scripts are only resolved and
 * handed to host.php, which runs them at file scope.
 */
final class LegacyBridgeController
{
    public function __construct(
        private readonly LegacyBridge $bridge,
        private readonly RouteOwnership $ownership,
    ) {
    }

    #[Route('/{path}', name: RouteOwnership::LEGACY_ROUTE, requirements: ['path' => '.*'], defaults: ['path' => ''], priority: -1000)]
    public function __invoke(Request $request): Response
    {
        $allowed = $this->ownership->allowedMethods($request);
        if ($allowed !== []) {
            throw new MethodNotAllowedHttpException($allowed);
        }

        $resolution = $this->bridge->resolve($request);

        return $resolution->isDirectAsset()
            ? $this->bridge->createAssetResponse($request, $resolution)
            : new LegacyExecutionResponse($resolution);
    }
}
