<?php

declare(strict_types=1);

namespace App\Routing;

use Illuminate\Http\Request;

/**
 * A condition a route needs besides its method and path, attached with
 * `->matching(Condition::class)`; the equivalent of a Symfony route condition.
 * Implementations are resolved from the container on every match.
 */
interface RouteCondition
{
    public function matches(Request $request): bool;
}
