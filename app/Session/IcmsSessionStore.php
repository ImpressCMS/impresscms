<?php

declare(strict_types=1);

namespace App\Session;

use App\Legacy\LegacyRuntime;
use Illuminate\Session\NullSessionHandler;
use Illuminate\Session\Store;

/**
 * Laravel's session API over the session ImpressCMS owns (design L10).
 *
 * Laravel's own store reads and writes a handler of its own under a cookie of
 * its own. This one works on $_SESSION instead: start() boots the `services`
 * stage, where Icms\Core\Session sets the session name, lifetime and database
 * handler and starts the PHP session, and then binds the attributes to
 * $_SESSION by reference. PHP writes the session at shutdown, through the
 * ImpressCMS handler, so save() only ages the flash data.
 *
 * Creating the store does nothing: Laravel resolves the session store for
 * every redirector, also for requests that never touch the session.
 */
final class IcmsSessionStore extends Store
{
    public function __construct(private readonly LegacyRuntime $runtime)
    {
        parent::__construct('', new NullSessionHandler());
    }

    public function start(): bool
    {
        $this->runtime->bootTo('services');

        if (session_status() !== PHP_SESSION_ACTIVE) {
            return $this->started = false;
        }

        $this->attributes = &$_SESSION;

        if (! $this->has('_token')) {
            $this->regenerateToken();
        }

        return $this->started = true;
    }

    public function save(): void
    {
        if ($this->started) {
            $this->ageFlashData();
        }

        $this->started = false;
    }

    public function getId(): string
    {
        return (string) session_id();
    }

    /**
     * @param string|null $id
     */
    public function setId($id): void
    {
        // the id is PHP's, chosen by ImpressCMS
    }

    public function getName(): string
    {
        return (string) session_name();
    }

    public function migrate($destroy = false): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return false;
        }

        return session_regenerate_id((bool) $destroy);
    }
}
