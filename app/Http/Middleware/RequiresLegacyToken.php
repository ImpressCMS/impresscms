<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Legacy\LegacyRuntime;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * `->middleware('icms.token')`: a POST must carry a valid ImpressCMS security
 * token (icms::$security->check()), which is used up. The equivalent of the
 * Symfony variant's #[RequiresLegacyToken]; Laravel's own CSRF token is not
 * used (design L12).
 */
final class RequiresLegacyToken
{
    public function __construct(private readonly LegacyRuntime $runtime)
    {
    }

    /**
     * @param Closure(Request): Response $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('POST')) {
            return $next($request);
        }

        // the token names and messages are defined by the core language files
        $this->runtime->bootTo('language');
        if (! $this->runtime->service('security')->check()) {
            abort(Response::HTTP_FORBIDDEN, 'Invalid or expired ImpressCMS security token.');
        }

        return $next($request);
    }
}
