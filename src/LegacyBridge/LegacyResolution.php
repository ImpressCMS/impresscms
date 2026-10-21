<?php

declare(strict_types=1);

namespace App\LegacyBridge;

/**
 * Where a legacy-owned request leads: a static asset, a PHP script, or the
 * legacy front controller for paths that do not exist.
 */
final class LegacyResolution
{
    public const DIRECT_ASSET = 'direct_asset';
    public const DIRECT_SCRIPT = 'direct_script';
    public const FALLBACK_FRONT_CONTROLLER = 'fallback_front_controller';

    public function __construct(
        public readonly string $strategy,
        public readonly string $requestPath,
        public readonly string $resolvedPath,
        public readonly string $workingDirectory,
        public readonly string $scriptName,
    ) {
    }

    public function isDirectAsset(): bool
    {
        return $this->strategy === self::DIRECT_ASSET;
    }
}
