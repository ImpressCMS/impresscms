<?php

declare(strict_types=1);

/*
 * ImpressCMS owns the session (design L10): name, lifetime, cookie and the
 * database handler are its own, and the `icms` driver gives Laravel's session
 * API $_SESSION. Laravel resolves the store for every redirector, so creating
 * it must not boot legacy; only starting it does.
 */
return [
    'driver' => 'icms',
];
