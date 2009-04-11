<?php
/**
* Installer language selection page
*
* See the enclosed file license.txt for licensing information.
* If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
*
* @copyright    The XOOPS project http://www.xoops.org/
* @license      http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
* @package		installer
* @since        Xoops 2.3.0
* @author		Haruki Setoyama  <haruki@planewave.org>
* @author 		Kazumi Ono <webmaster@myweb.ne.jp>
* @author		Skalpa Keo <skalpa@xoops.org>
* @version		$Id$
*/
/**
 *
 */ 
require_once 'common.inc.php';
if ( !defined( 'XOOPS_INSTALL' ) )	exit();

$wizard->setPage( 'langselect' );

/*
 * gets list of name of directories inside a directory
 */
function getDirList($dirname) {
    $dirlist = array();
    if ( $handle = opendir($dirname) ) {
        while ( $file = readdir($handle) ) {
        	if ( $file{0} != '.' && is_dir( $dirname . $file ) ) {
        		$dirlist[] = $file;
        	}
        }
        closedir($handle);
        asort($dirlist);
        reset($dirlist);
    }
    return $dirlist;
}


if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	$lang = $_REQUEST['lang'];

	$languages = getDirList( "./language/" );
	if (!in_array($lang, $languages)) {
		$lang = 'english';
	}
	setcookie( 'xo_install_lang', $lang, null, null, null );

	$wizard->redirectToPage( '+1' );
	exit();
}
	$_SESSION = array();
	$pageHasForm = true;
	$title = LANGUAGE_SELECTION;
	$content = '<select name="lang" size="10" style="min-width:10em">';

	$languages = getDirList( "./language/" );
	foreach ( $languages as $lang ) {
		$sel = ( $lang == $wizard->language ) ? ' selected="selected"' : '';
		$content .= "<option value=\"$lang\"$sel>$lang</option>\n";
	}
	$content .= "</select>";

	include 'install_tpl.php';
?>