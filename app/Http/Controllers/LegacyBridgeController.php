<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\LegacyBridge\LegacyBridge;
use App\LegacyBridge\LegacyExecutionResponse;
use App\Routing\RouteOwnership;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

/**
 * The fallback route: every request that no Laravel route claims is legacy
 * ImpressCMS. Assets are served here; scripts are only resolved and handed
 * to host.php, which runs them at file scope.
 */
final class LegacyBridgeController
{
    public function __construct(
        private readonly LegacyBridge $bridge,
        private readonly RouteOwnership $ownership,
    ) {
    }

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
