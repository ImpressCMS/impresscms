<?php

declare(strict_types=1);

namespace App\Admin;

use Symfony\Component\HttpFoundation\Response;

/**
 * Renders a framework-handled page inside the ImpressCMS admin theme
 * (design D4), with the legacy admin chrome: icms_cp_header() and
 * icms_cp_footer() build the theme, the admin menu, the preloads and their
 * headers, exactly as on a legacy admin page. Nothing exits and nothing is
 * sent: the page comes back as a Response (App\Admin\LegacyOutput).
 *
 * Needs the admin context (#[SystemAdminFunction]), which loads
 * include/cp_functions.php.
 */
final class AdminPageRenderer
{
    public function __construct(private readonly LegacyOutput $output)
    {
    }

    /**
     * A page with a content template, the way legacy admin pages do it:
     * icms_cp_header(), $build assigns to the admin template,
     * $icmsAdminTpl->display($contentTemplate), icms_cp_footer().
     *
     * @param \Closure(object $icmsAdminTpl): void $build
     */
    public function render(string $contentTemplate, \Closure $build): Response
    {
        return $this->output->capture(static function () use ($contentTemplate, $build): void {
            \icms_cp_header();
            $template = $GLOBALS['icmsAdminTpl'];
            $build($template);
            $template->display($contentTemplate);
            \icms_cp_footer();
        });
    }

    /**
     * A page whose content legacy code echoes itself between the admin header
     * and footer, such as Icms\Core\Message::confirm().
     *
     * @param \Closure(): void $echo
     */
    public function renderFragment(\Closure $echo): Response
    {
        return $this->output->capture(static function () use ($echo): void {
            \icms_cp_header();
            $echo();
            \icms_cp_footer();
        });
    }
}
