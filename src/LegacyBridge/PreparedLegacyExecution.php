<?php

declare(strict_types=1);

namespace App\LegacyBridge;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * A legacy script that is about to run, with the host state to restore afterwards.
 */
final class PreparedLegacyExecution
{
    public bool $completed = false;
    public ?Response $response = null;

    /** The exception handler before the script ran; one the script installs differs. */
    public mixed $hostExceptionHandler = null;

    /**
     * @param array<string, mixed>     $snapshot host state captured by LegacyRequestHydrator
     * @param \Closure(Response): void $finisher sends the response and terminates the kernel
     */
    public function __construct(
        public readonly Request $request,
        public readonly LegacyResolution $resolution,
        public readonly array $snapshot,
        public readonly int $outputBufferLevel,
        public readonly \Closure $finisher,
    ) {
    }

    public function script(): string
    {
        return $this->resolution->resolvedPath;
    }
}
