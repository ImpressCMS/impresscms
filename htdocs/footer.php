<?php
/**
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		core
* @since		XOOPS
* @author		http://www.xoops.org The XOOPS Project
* @author	   Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
* @version		$Id$
*/

if(!defined('ICMS_ROOT_PATH')) {die('ICMS root path not defined');}
if(!defined("XOOPS_FOOTER_INCLUDED"))
{
	/** Set the constant XOOPS_FOOTER_INCLUDED to 1 - this file has been included */
  define("XOOPS_FOOTER_INCLUDED",1);

	// ################# Preload Trigger beforeFooter ##############
	$icmsPreloadHandler->triggerEvent('beforeFooter');

	$xoopsLogger->stopTime('Module display');
	if($xoopsOption['theme_use_smarty'] == 0)
	{
		// the old way
		$footer = htmlspecialchars($xoopsConfigMetaFooter['footer']).'<br /><div style="text-align:center">Powered by ImpressCMS &copy; 2007-'.date('Y').' <a href="http://www.impresscms.org/" rel="external">ImpressCMS</a></div>';
		$google_analytics = $xoopsConfigMetaFooter['google_analytics'];

		if(isset($xoopsOption['template_main']))
		{
			$xoopsTpl->xoops_setCaching(0);
			$xoopsTpl->display('db:'.$xoopsOption['template_main']);
		}
		if(!isset($xoopsOption['show_rblock'])) {$xoopsOption['show_rblock'] = 0;}
		themefooter($xoopsOption['show_rblock'], $footer, $google_analytics);
		xoops_footer();
	}
	else
	{
		// RMV-NOTIFY
		if (is_object($xoopsModule) && $xoopsModule->getVar('hasnotification') == 1 && is_object($xoopsUser)) {
			/** Require the notifications area */
      require_once 'include/notification_select.php';
		}
    /** @todo Notifications include/require clarification in footer.php - if this is included here, why does it need to be required above? */
    /** Include the notifications area */
		include_once ICMS_ROOT_PATH . '/include/notification_select.php';

		if(!headers_sent())
		{
			header('Content-Type:text/html; charset='._CHARSET);
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Cache-Control: private, no-cache');
			header('Pragma: no-cache');
		}
		/*
		global $xoopsDB, $xoopsConfig;
		if(!$xoopsConfig['theme_fromfile'])
		{
			session_write_close();
			$xoopsDB->close();
		}
		*/
		//@internal: using global $xoTheme dereferences the variable in old versions, this does not
		if(!isset($xoTheme)) {$xoTheme =& $GLOBALS['xoTheme'];}
		if(isset($xoopsOption['template_main']) && $xoopsOption['template_main'] != $xoTheme->contentTemplate)
		{
			trigger_error("xoopsOption[template_main] should be defined before including header.php", E_USER_WARNING);
			if(false === strpos($xoopsOption['template_main'], ':'))
			{
				$xoTheme->contentTemplate = 'db:'.$xoopsOption['template_main'];
			}
			else
			{
				$xoTheme->contentTemplate = $xoopsOption['template_main'];
			}
		}
	$config_handler = & xoops_gethandler ( 'config' );
	$xoopsConfigMetaFooter = & $config_handler->getConfigsByCat ( XOOPS_CONF_METAFOOTER );
	if ($xoopsConfigMetaFooter['use_google_analytics'] == 1 && isset($xoopsConfigMetaFooter['google_analytics']) && $xoopsConfigMetaFooter['google_analytics'] != ''){
		$xoTheme->addScript('<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
			</script>
	<script type="text/javascript">
_uacct = "UA-'.$xoopsConfigMetaFooter['google_analytics'].'";
urchinTracker();
</script>');
	}
		$xoTheme->render();
	}
	$xoopsLogger->stopTime();
}
?>