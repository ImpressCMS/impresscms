<?php

declare(strict_types=1);

namespace App\Session;

use App\Legacy\LegacyRuntime;
use Illuminate\Session\SessionManager;
use Illuminate\Session\Store;

/**
 * Laravel's session manager with the `icms` driver (design L10).
 *
 * SessionManager::extend() cannot add it: a custom creator must return a
 * session *handler*, which the manager wraps in its own Store. The `icms`
 * driver is a Store of its own, so the manager is replaced
 * (IcmsServiceProvider) and the driver is a create…Driver() method.
 */
final class IcmsSessionManager extends SessionManager
{
    protected function createIcmsDriver(): Store
    {
        return new IcmsSessionStore($this->container->make(LegacyRuntime::class));
    }
}
