<?php
// $Id: footer.php 1083 2007-10-16 16:42:51Z phppp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}
if ( !defined("XOOPS_FOOTER_INCLUDED") ) {
	define("XOOPS_FOOTER_INCLUDED",1);

	$xoopsLogger->stopTime( 'Module display' );
	if ($xoopsOption['theme_use_smarty'] == 0) {
		// the old way
		$footer = htmlspecialchars( $xoopsConfigMetaFooter['footer'] ) . "<br /><div style='text-align:center'>Powered by&nbsp;".XOOPS_VERSION." &copy; 2007-".date("Y")." <a href='http://www.impresscms.org/' target='_blank'>ImpressCMS</a></div>";
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
		include_once XOOPS_ROOT_PATH . '/include/notification_select.php';

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
		$xoTheme->render();
	}
	$xoopsLogger->stopTime();
}
?>