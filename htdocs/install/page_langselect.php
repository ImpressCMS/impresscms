<?php
/**
 * Installer language selection page
 *
 * See the enclosed file license.txt for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package		installer
 * @since       2.3.0
 * @author		Haruki Setoyama  <haruki@planewave.org>
 * @author 		Kazumi Ono <webmaster@myweb.ne.jp>
 * @author		Skalpa Keo <skalpa@xoops.org>
 * @author		Taiwen Jiang <phppp@users.sourceforge.net>
 * @version		$Id: page_langselect.php 12329 2013-09-19 13:53:36Z skenow $
 */

/**
 *
 */
require_once 'common.inc.php';
if (!defined( 'XOOPS_INSTALL' ) )	exit();

$wizard->setPage( 'langselect' );

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$lang = htmlentities($_REQUEST['lang']);

	$languages = icms_core_Filesystem::getDirList( "./language/" );
	if (!in_array($lang, $languages)) {
		$lang = 'english';
	}
	setcookie( 'xo_install_lang', $lang, time() + 3600, '/', null );

	$wizard->redirectToPage( '+1' );
	exit();
}
$_SESSION = array();
$pageHasForm = true;
$pageHasHelp = false;
$title = LANGUAGE_SELECTION;
$content = "";


$languages = icms_core_Filesystem::getDirList( "./language/" );
$content .= '<fieldset class="lang-grid"><legend>' . LANGUAGE_SELECTION . '</legend>';
foreach ( $languages as $lang) {
	$sel = ( $lang == $wizard->language ) ? ' checked="checked"' : '';
	$langName = htmlspecialchars($lang, ENT_QUOTES);
	$content .= "<label class=\"choice\"><img src=\"../images/flags/$langName.gif\" alt=\"\" /><span>$langName</span><input type=\"radio\" name=\"lang\" value=\"$langName\"$sel /></label>";
}
$content .= '</fieldset>';
$content .= '<p>' . ALTERNATE_LANGUAGE_MSG . ' <a href="' . ALTERNATE_LANGUAGE_LNK_URL . '" target="_blank" rel="noopener">' . ALTERNATE_LANGUAGE_LNK_MSG . '</a></p>';

include 'install_tpl.php';