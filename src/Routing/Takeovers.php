<?php

declare(strict_types=1);

namespace App\Routing;

use Symfony\Bundle\FrameworkBundle\Routing\Attribute\AsRoutingConditionService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * Switches individual takeovers of legacy URLs on and off (design D2).
 *
 * A route that takes over a legacy page names a takeover key in its condition:
 *
 *     condition: "... and service('icms_takeovers').enabled('system.mimetype')"
 *
 * The keys listed in ICMS_TAKEOVERS_DISABLED (comma-separated, in
 * <trust>/.env.local) are off, so their URLs fall through to legacy again.
 * Route conditions are evaluated on every request and environment variables
 * are read when the container creates this service, so a change applies to
 * the next request, without a cache clear.
 */
#[AsRoutingConditionService(alias: 'icms_takeovers')]
final class Takeovers
{
    public const ENV = 'ICMS_TAKEOVERS_DISABLED';

    /** @var array<string, true> */
    private readonly array $disabled;

    private bool $allEnabled = false;

    public function __construct(
        #[Autowire(env: 'default::' . self::ENV)]
        ?string $disabled = null,
    ) {
        $keys = array_filter(array_map('trim', explode(',', (string) $disabled)), static fn (string $key): bool => $key !== '');
        $this->disabled = array_fill_keys($keys, true);
    }

    public function enabled(string $key): bool
    {
        return $this->allEnabled || !isset($this->disabled[$key]);
    }

    /**
     * @return list<string>
     */
    public function disabledKeys(): array
    {
        return array_keys($this->disabled);
    }

    /**
     * Run $callback as if no takeover were disabled, for example to find the
     * route a disabled takeover would match.
     *
     * @template T
     *
     * @param \Closure(): T $callback
     *
     * @return T
     */
    public function whileAllEnabled(\Closure $callback): mixed
    {
        $previous = $this->allEnabled;
        $this->allEnabled = true;
        try {
            return $callback();
        } finally {
            $this->allEnabled = $previous;
        }
    }

    /**
     * The takeover keys a route condition names.
     *
     * @return list<string>
     */
    public static function keysInCondition(string $condition): array
    {
        preg_match_all("/service\\(\\s*['\"]icms_takeovers['\"]\\s*\\)\\.enabled\\(\\s*['\"]([^'\"]+)['\"]\\s*\\)/", $condition, $matches);

        return array_values(array_unique($matches[1]));
    }
}
