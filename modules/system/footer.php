<?php
/**
 * Footer page included at the end of each page on user side of the mdoule
 *
 * @copyright	ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Steve Kenow <skenow@impresscms.org>
 * @package		cpanel
 * @version		$Id$
 */

defined("ICMS_ROOT_PATH") or die("ICMS root path not defined");

$icmsTpl->assign("cpanel_adminpage", "<a href='" . ICMS_MODULES_URL . "/" . icms::$module->getVar("dirname") . "/admin/index.php'>" ._MD_CPANEL_ADMIN_PAGE . "</a>");
$icmsTpl->assign("cpanel_is_admin", icms_userIsAdmin(CPANEL_DIRNAME));
$icmsTpl->assign('cpanel_url', CPANEL_URL);
$icmsTpl->assign('cpanel_images_url', CPANEL_IMAGES_URL);

$xoTheme->addStylesheet(CPANEL_URL . 'module' . ((defined("_ADM_USE_RTL") && _ADM_USE_RTL) ? '_rtl' : '') . '.css');

include_once ICMS_ROOT_PATH . '/footer.php';