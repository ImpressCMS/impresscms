<?php
/**
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	core
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version	$Id$
 */

if (!defined('ICMS_ROOT_PATH')) {die('ICMS root path not defined');}
if (!defined("XOOPS_FOOTER_INCLUDED"))
{
	global $xoopsOption, $icmsConfigMetaFooter, $xoopsTpl, $icmsModule;

	/** Set the constant XOOPS_FOOTER_INCLUDED to 1 - this file has been included */
	define("XOOPS_FOOTER_INCLUDED",1);

	$_SESSION['ad_sess_regen'] = false;
	if (isset($_SESSION['sess_regen']) && $_SESSION['sess_regen']) {
		icms::$session->sessionOpen(true);
		$_SESSION['sess_regen'] = false;
	} else {
		icms::$session->sessionOpen();
	}

	// ################# Preload Trigger beforeFooter ##############
	icms::$preload->triggerEvent('beforeFooter');

	icms::$logger->stopTime('Module display');
	if (isset($xoopsOption['theme_use_smarty']) && $xoopsOption['theme_use_smarty'] == 0)
	{
		// the old way
		$footer = htmlspecialchars($icmsConfigMetaFooter['footer']).'<br /><div style="text-align:center">Powered by ImpressCMS &copy; 2007-'.date('Y').' <a href="http://www.impresscms.org/" rel="external">ImpressCMS</a></div>';
		$google_analytics = $icmsConfigMetaFooter['google_analytics'];

		if (isset($xoopsOption['template_main']))
		{
			$xoopsTpl->caching = 0;
			$xoopsTpl->display('db:'.$xoopsOption['template_main']);
		}
		if (!isset($xoopsOption['show_rblock'])) {$xoopsOption['show_rblock'] = 0;}
		//themefooter($xoopsOption['show_rblock'], $footer, $google_analytics);
		xoops_footer();
	} else {
		// RMV-NOTIFY
		if (is_object($icmsModule) && $icmsModule->getVar('hasnotification') == 1 && is_object(icms::$user)) {
			/** Require the notifications area */
			require_once 'include/notification_select.php';
		}
		/** @todo Notifications include/require clarification in footer.php - if this is included here, why does it need to be required above? */
		/** Include the notifications area */
		include_once ICMS_ROOT_PATH . '/include/notification_select.php';

		if (!headers_sent())
		{
			header('Content-Type:text/html; charset='._CHARSET);
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Cache-Control: private, no-cache');
			header('Pragma: no-cache');
		}
		/*
		 global $icmsConfig;
		 if (!$icmsConfig['theme_fromfile'])
		 {
			session_write_close();
			icms::$xoopsDB->close();
			}
			*/
		//@internal: using global $xoTheme dereferences the variable in old versions, this does not
		if (!isset($xoTheme)) {$xoTheme =& $GLOBALS['xoTheme'];}
		if (isset($xoopsOption['template_main']) && $xoopsOption['template_main'] != $xoTheme->contentTemplate)
		{
			trigger_error("xoopsOption[template_main] should be defined before including header.php", E_USER_WARNING);
			if (false === strpos($xoopsOption['template_main'], ':'))
			{
				$xoTheme->contentTemplate = 'db:'.$xoopsOption['template_main'];
			} else {
				$xoTheme->contentTemplate = $xoopsOption['template_main'];
			}
		}
		$xoTheme->render();
	}
	icms::$logger->stopTime();
}
?>