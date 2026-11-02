<?php

declare(strict_types=1);

/*
 * Laravel resolves its session store whenever the redirector is built, which
 * every response() and redirect() helper does, also for error pages. Its
 * default `database` driver would then open the ImpressCMS connection and boot
 * legacy for a 404. ImpressCMS owns the session (design L10).
 */
return [
    'driver' => 'array',
];
