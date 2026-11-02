<?php

declare(strict_types=1);

namespace App\Http\Controllers\Test;

use App\Routing\RouteCondition;
use Illuminate\Http\Request;

/**
 * Bridge tests that need a claimed legacy URL themselves send
 * `X-Icms-Test-Takeover: off`.
 */
final class TakeoverNotSuppressed implements RouteCondition
{
    public function matches(Request $request): bool
    {
        return $request->headers->get('X-Icms-Test-Takeover') !== 'off';
    }
}
