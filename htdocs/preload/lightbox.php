<?php
/**
* Initiating Lightbox library
*
* This file is responsible for initiating the Lightbox library
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		libraries
* @since		1.2
* @author		TheRplima <therplima@impresscms.org>
* @version		$Id: lightbox.php 1742 2008-04-20 14:46:20Z real_therplima $
*/

class IcmsPreloadLightbox extends IcmsPreloadItem
{
	function finishOutputInit() {
		global $xoTheme;

		$xoTheme->addScript(ICMS_LIBRARIES_URL . '/lightbox/js/lightbox.js');
		$js  = 'var fileLoadingImage = "'.ICMS_LIBRARIES_URL.'/lightbox/images/loading.gif";';
		$js .= 'var fileBottomNavCloseImage = "'.ICMS_LIBRARIES_URL.'/lightbox/images/close.gif";';
		$xoTheme->addScript('','',$js);
		$xoTheme->addStylesheet(ICMS_LIBRARIES_URL . '/lightbox/css/lightbox.css');
	}
	
	function eventadminBeforeFooter() {
		$ret  = '<script type="text/javascript" src="'.ICMS_LIBRARIES_URL.'/lightbox/js/lightbox.js"></script>';
		$ret .= '<script type="text/javascript">var fileLoadingImage = "'.ICMS_LIBRARIES_URL.'/lightbox/images/loading.gif";';
		$ret .= 'var fileBottomNavCloseImage = "'.ICMS_LIBRARIES_URL.'/lightbox/images/close.gif";</script>';

		$ret .= '<link rel="stylesheet" type="text/css" media="all" href="'.ICMS_LIBRARIES_URL.'/lightbox/css/lightbox.css" />';

		echo $ret;
	}
}
?>