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
* @author		modified by stranger <stranger@impresscms.ir>
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}
if ( !defined("XOOPS_FOOTER_INCLUDED") ) {
	define("XOOPS_FOOTER_INCLUDED",1);

	// ################# Preload Trigger beforeFooter ##############
	$icmsPreloadHandler->triggerEvent('beforeFooter');
	
	$xoopsLogger->stopTime( 'Module display' );
	if ($xoopsOption['theme_use_smarty'] == 0) {
		// the old way
		$footer = htmlspecialchars( $xoopsConfigMetaFooter['footer'] ) . '<br /><div style="text-align:center">Powered by&nbsp;'.XOOPS_VERSION.' &copy; 2007-'.date('Y').' <a href="http://www.impresscms.org/" rel="external">ImpressCMS</a></div>';

		if (isset($xoopsOption['template_main'])) {
			$xoopsTpl->xoops_setCaching(0);
			$xoopsTpl->display('db:'.$xoopsOption['template_main']);
		}
		if (!isset($xoopsOption['show_rblock'])) {
			$xoopsOption['show_rblock'] = 0;
		}
		themefooter($xoopsOption['show_rblock'], $footer);
		xoops_footer();
	} else {
		// RMV-NOTIFY
		include_once ICMS_ROOT_PATH . '/include/notification_select.php';
		
		if (!headers_sent()) {
			header('Content-Type:text/html; charset='._CHARSET);
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			//header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
			header('Cache-Control: private, no-cache');
			header('Pragma: no-cache');
		}
		/*
		global $xoopsDB, $xoopsConfig;
		if ( !$xoopsConfig['theme_fromfile'] ) {
			session_write_close();
			$xoopsDB->close();
		}
		*/
		//@internal: using global $xoTheme dereferences the variable in old versions, this does not
		if ( !isset( $xoTheme ) )	$xoTheme =& $GLOBALS['xoTheme'];

		if ( isset( $xoopsOption['template_main'] ) && $xoopsOption['template_main'] != $xoTheme->contentTemplate ) {
			trigger_error( "xoopsOption[template_main] should be defined before including header.php", E_USER_WARNING );
			if ( false === strpos( $xoopsOption['template_main'], ':' ) ) {
				$xoTheme->contentTemplate = 'db:' . $xoopsOption['template_main'];
			} else {
				$xoTheme->contentTemplate = $xoopsOption['template_main'];
			}
		}
				     	// Start addition: assign the language css&Javascripts file to the template, if required
        if ( defined('_ADM_USE_RTL') && _ADM_USE_RTL && file_exists(ICMS_ROOT_PATH."/xoops_rtl.css") ) {
            $xoTheme->addStylesheet( "/xoops_rtl.css", array( "media" => "screen" ) );
        }
        if ( defined('_ADM_USE_RTL') && _ADM_USE_RTL && file_exists(ICMS_ROOT_PATH."/include/xoops_rtl.js") ) {
            $xoTheme->addScript( "/include/xoops_rtl.js", array( "type" => "text/javascript" ) );
        }
        // End addition
		$xoTheme->render();
	}
	$xoopsLogger->stopTime();
}
?>