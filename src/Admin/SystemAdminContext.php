<?php

declare(strict_types=1);

namespace App\Admin;

use App\Legacy\LegacyRuntime;
use App\LegacyBridge\LegacyRequestHydrator;
use App\LegacyBridge\LegacyScriptResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Enters a system admin function the way modules/system/admin.php does
 * (design D3, D5), step by step:
 *
 * 1. the superglobals and working directory of admin.php (as the legacy bridge
 *    sets them for scripts), so legacy code sees SCRIPT_NAME, PHP_SELF and the
 *    module directory it expects
 * 2. ICMS_IN_ADMIN, then the boot up to the closed-site check
 * 3. the module launch's admin check: visitors who do not administer the system
 *    module get its denial, redirect_header(ICMS_URL/user.php, _NOPERM). It is
 *    checked here because the launch itself would exit() for them
 * 4. the rest of the boot (module launch, online users)
 * 5. include/cp_functions.php and the language files system/admin,
 *    core/moduleabout and system/<fct>, then modules/system/constants.php
 * 6. the function's category right; without it, admin.php's
 *    `Location: ICMS_URL/admin.php`
 *
 * ICMS_IN_ADMIN is process-wide, which is fine with one request per process.
 * The host's superglobals and working directory are restored by leave().
 */
final class SystemAdminContext
{
    /** @var array<string, mixed>|null */
    private ?array $hostState = null;

    public function __construct(
        private readonly LegacyRuntime $runtime,
        private readonly LegacyScriptResolver $resolver,
        private readonly LegacyRequestHydrator $hydrator,
        private readonly SystemAdminAccess $access,
        private readonly LegacyRedirect $redirect,
        private readonly LegacyOutput $output,
    ) {
    }

    /**
     * @return Response|null the legacy denial, or null when the function may be used
     */
    public function enter(string $fct, Request $request): ?Response
    {
        // the first boot overrides the superglobals with the request; admin.php's come after
        $this->runtime->bootTo('constants');
        $this->hydrateAsAdminScript($request);

        if (!defined('ICMS_IN_ADMIN')) {
            define('ICMS_IN_ADMIN', 1);
        }
        $this->runtime->bootTo('siteState');

        $groups = $this->access->currentGroups();
        if (!$this->access->administersSystemModule($groups)) {
            return $this->redirect->to(ICMS_URL . '/user.php', 3, _NOPERM, false);
        }

        $this->runtime->bootTo('online');
        include_once ICMS_ROOT_PATH . '/include/cp_functions.php';
        icms_loadLanguageFile('system', 'admin');
        icms_loadLanguageFile('core', 'moduleabout');
        // admin.php's global for the system module, read by admin pages
        $GLOBALS['icmsModule'] = \icms::handler('icms_module')->getByDirname('system');
        include_once ICMS_ROOT_PATH . '/modules/system/constants.php';

        if (!$this->access->functionExists($fct)) {
            return $this->redirect->to(ICMS_URL . '/', 3, _INVALID_ADMIN_FUNCTION);
        }
        if ($groups === null || !$this->access->mayUseFunction($fct, $groups)) {
            return $this->output->capture(static function (): void {
                header('Location:' . ICMS_URL . '/admin.php');
            });
        }

        return null;
    }

    /**
     * Restore the host's superglobals and working directory.
     */
    public function leave(): void
    {
        if ($this->hostState !== null) {
            $this->hydrator->restore($this->hostState);
            $this->hostState = null;
        }
    }

    private function hydrateAsAdminScript(Request $request): void
    {
        $resolution = $this->resolver->resolvePath('/modules/system/admin.php');
        $state = $this->hydrator->hydrate($request, $resolution, $this->resolver->legacyRoot());
        // as the client sent it: Request::overrideGlobals() (the first boot) sorts
        // the parameters, and legacy pages echo QUERY_STRING into links and forms
        $_SERVER['QUERY_STRING'] = (string) parse_url($request->getRequestUri(), PHP_URL_QUERY);
        // the session belongs to ImpressCMS, also after the request
        $state['session_active'] = false;
        $this->hostState ??= $state;
    }
}
