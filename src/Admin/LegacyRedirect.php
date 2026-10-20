<?php

declare(strict_types=1);

namespace App\Admin;

use Symfony\Component\HttpFoundation\Response;

/**
 * redirect_header() without exit() (design D6): ends a framework-handled
 * legacy page with a message and a target URL, as legacy pages do.
 *
 * - With `use_custom_redirection` a 302 to the target; the message is kept in
 *   $_SESSION['redirect_message'] for the next page.
 * - Otherwise the redirect page (db:system_redirect.html) in the site theme,
 *   closed by icms_cp_footer() inside the admin (include/cp_functions.php is
 *   loaded) or by footer.php elsewhere.
 *
 * The steps, globals and local variable names are those of redirect_header(),
 * because the footer code reads them; only the exits are gone. Targets off the
 * site are replaced by the site's start page, more strictly than legacy: also
 * protocol-relative URLs and look-alike hosts.
 */
final class LegacyRedirect
{
    public function __construct(private readonly LegacyOutput $output)
    {
    }

    public function to(string $url, int $time = 3, string $message = '', bool $addredirect = true, bool $allowExternalLink = false): Response
    {
        return $this->output->capture(function () use ($url, $time, $message, $addredirect, $allowExternalLink): void {
            $this->redirectHeader($url, $time, $message, $addredirect, $allowExternalLink);
        });
    }

    /**
     * The URL to redirect to: $url if it stays on the site, otherwise the site URL.
     */
    public static function sameSite(string $url, string $siteUrl): string
    {
        $url = trim($url);
        if (preg_match('/[\x00-\x19]|about:|script:/i', $url)) {
            return $siteUrl;
        }
        $absolute = str_contains($url, '://') || str_starts_with($url, '//') || str_starts_with($url, '\\\\') || str_starts_with($url, '/\\');
        if (!$absolute) {
            return $url;
        }

        $target = parse_url(str_replace('\\', '/', $url));
        $site = parse_url($siteUrl);
        if (!is_array($target) || !is_array($site)) {
            return $siteUrl;
        }
        $sameOrigin = isset($target['host'], $site['host'])
            && strcasecmp($target['host'], $site['host']) === 0
            && ($target['port'] ?? null) === ($site['port'] ?? null)
            && !isset($target['user'])
            && in_array(strtolower($target['scheme'] ?? ''), ['http', 'https'], true);

        return $sameOrigin ? $url : $siteUrl;
    }

    /**
     * redirect_header() of include/functions.php, minus exit().
     */
    private function redirectHeader(string $url, int $time, string $message, bool $addredirect, bool $allowExternalLink): void
    {
        global $icmsConfig, $icmsConfigPersona;

        if (preg_match("/[\\0-\\31]|about:|script:/i", $url)) {
            if (preg_match('/^\b(java)?script:([\s]*)history\.go\(-[0-9]*\)([\s]*[;]*[\s]*)$/si', $url)) {
                $url = ICMS_URL;
            }
        }
        if (!$allowExternalLink) {
            $url = self::sameSite($url, ICMS_URL);
        }
        $theme = $icmsConfig['theme_set'];
        // if the user selected a theme in the theme block, let's use this theme
        if (isset($_SESSION['xoopsUserTheme']) && in_array($_SESSION['xoopsUserTheme'], $icmsConfig['theme_set_allowed'])) {
            $theme = $_SESSION['xoopsUserTheme'];
        }

        $xoopsThemeFactory = new \Icms\View\Theme\Factory();
        $xoopsThemeFactory->allowedThemes = $icmsConfig['theme_set_allowed'];
        $xoopsThemeFactory->defaultTheme = $theme;
        $icmsTheme = $xoTheme = &$xoopsThemeFactory->createInstance(['plugins' => []]);
        $xoopsTpl = $icmsTpl = &$xoTheme->template;

        if ($icmsConfig['debug_mode'] == 2 && is_object(\icms::$user) && \icms::$user->isAdmin()) {
            $xoopsTpl->assign('time', 300);
            $xoopsTpl->assign('xoops_logdump', \icms::$logger->dump());
        } else {
            $xoopsTpl->assign('time', $time);
        }
        if (!empty($_SERVER['REQUEST_URI']) && $addredirect && strstr($url, 'user.php')) {
            if (!strstr($url, '?')) {
                $url .= '?xoops_redirect=' . urlencode($_SERVER['REQUEST_URI']);
            } else {
                $url .= '&amp;xoops_redirect=' . urlencode($_SERVER['REQUEST_URI']);
            }
        }
        $sid = defined('SID') ? (string) constant('SID') : '';
        if ($sid !== '' && (!isset($_COOKIE[session_name()]) || ($icmsConfig['use_mysession'] && $icmsConfig['session_name'] != '' && !isset($_COOKIE[$icmsConfig['session_name']])))) {
            if (!strstr($url, '?')) {
                $url .= '?' . $sid;
            } else {
                $url .= '&amp;' . $sid;
            }
        }
        $url = preg_replace('/&amp;/i', '&', htmlspecialchars($url, ENT_QUOTES));
        $xoopsTpl->assign('url', $url);
        $message = trim($message) != '' ? $message : _TAKINGBACK;
        $xoopsTpl->assign('message', $message);
        $xoopsTpl->assign('lang_ifnotreload', sprintf(_IFNOTRELOAD, $url));

        if (!headers_sent() && $icmsConfigPersona['use_custom_redirection'] == 1) {
            $_SESSION['redirect_message'] = $message;
            header('Location: ' . preg_replace('/[&]amp;/i', '&', $url));

            return;
        }

        $xoopsTpl->display('db:system_redirect.html');
        if (defined('XOOPS_CPFUNC_LOADED')) {
            \icms_cp_footer();
        } else {
            // footer.php reads $xoTheme and $xoopsTpl of this scope
            include ICMS_ROOT_PATH . '/footer.php';
        }
    }
}
