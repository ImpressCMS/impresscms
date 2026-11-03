<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Admin\SystemAdminContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * `icms.admin:<fct>`: runs the route as the system admin function <fct>, with
 * the boot, access rules and denials of modules/system/admin.php (design L27,
 * L28). It replaces `icms.legacy` and must not be combined with `auth`: the
 * guard would launch the module, which exits for denied visitors.
 */
final class SystemAdminFunction
{
    public function __construct(private readonly SystemAdminContext $context)
    {
    }

    public function handle(Request $request, Closure $next, string $fct): Response
    {
        try {
            return $this->context->enter($fct, $request) ?? $next($request);
        } finally {
            $this->context->leave();
        }
    }
}
