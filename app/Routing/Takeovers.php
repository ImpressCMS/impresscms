<?php

declare(strict_types=1);

namespace App\Routing;

use Closure;

/**
 * The switch for takeovers of legacy URLs (design L26). A route names its
 * takeover with `->takeover('system.mimetype')`; while that key is listed in
 * `icms.takeovers.disabled` (ICMS_TAKEOVERS_DISABLED), the route does not
 * match and legacy serves its URLs.
 */
final class Takeovers
{
    private bool $ignoringSwitch = false;

    public function enabled(string $key): bool
    {
        if ($this->ignoringSwitch) {
            return true;
        }

        return ! in_array($key, $this->disabled(), true);
    }

    /** @return list<string> */
    public function disabled(): array
    {
        return array_values((array) config('icms.takeovers.disabled', []));
    }

    /**
     * Runs $callback as if every takeover were enabled, to find the takeover
     * a request would reach.
     *
     * @template T
     * @param Closure(): T $callback
     * @return T
     */
    public function ignoringSwitch(Closure $callback): mixed
    {
        $this->ignoringSwitch = true;

        try {
            return $callback();
        } finally {
            $this->ignoringSwitch = false;
        }
    }
}
