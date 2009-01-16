<?php
/**
 * Site index aka home page.
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		core
 * @since		XOOPS
 * @author		http://www.xoops.org The XOOPS Project
 * @author	    Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		$Id$
 **/
/**
 * redirects to installation, if ImpressCMS is not installed yet
 **/
include "mainfile.php";

$member_handler = & xoops_gethandler ( 'member' );
$group = $member_handler->getUserBestGroup((@is_object($xoopsUser)?$xoopsUser->uid():0));
$xoopsConfig ['startpage'] = $xoopsConfig ['startpage'] [$group];

if (isset ( $xoopsConfig ['startpage'] ) && $xoopsConfig ['startpage'] != "" && $xoopsConfig ['startpage'] != "--") {
	$arr = explode ( '-', $xoopsConfig ['startpage'] );
	if (count ( $arr ) > 1) {
		$page_handler = & xoops_gethandler ( 'page' );
		$page = $page_handler->get ( $arr [1] );
		if (is_object ( $page )) {
			$url = (substr ( $page->getVar ( 'page_url' ), 0, 7 ) == 'http://') ? $page->getVar ( 'page_url' ) : ICMS_URL . '/' . $page->getVar ( 'page_url' );
			header ( 'Location: ' . $url );
		} else {
			$xoopsConfig ['startpage'] = '--';
			$xoopsOption ['show_cblock'] = 1;
			/** Included to start page rendering */
			include "header.php";
			/** Included to complete page rendering */
			include "footer.php";
		}
	} else {
		header ( 'Location: ' . ICMS_URL . '/modules/' . $xoopsConfig ['startpage'] . '/' );
	}
	exit ();
} else {
	$xoopsOption ['show_cblock'] = 1;
	/** Included to start page rendering */
	include "header.php";
	/** Included to complete page rendering */
	include "footer.php";
}
?>