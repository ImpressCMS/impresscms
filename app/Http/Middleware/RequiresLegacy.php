<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Legacy\LegacyRuntime;
use Closure;
use Icms\Kernel\Bootstrap;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * `->middleware('icms.legacy:<stage>')`: boots ImpressCMS headless up to
 * that stage before the controller runs; without a stage, every stage. The
 * equivalent of the Symfony variant's #[RequiresLegacy] (design L6).
 */
final class RequiresLegacy
{
    public function __construct(private readonly LegacyRuntime $runtime)
    {
    }

    /**
     * @param Closure(Request): Response $next
     */
    public function handle(Request $request, Closure $next, ?string $stage = null): Response
    {
        $this->runtime->bootTo($stage ?? Bootstrap::STAGES[array_key_last(Bootstrap::STAGES)]);

        return $next($request);
    }
}
