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
 */

/**
 *
 */
require_once 'common.inc.php';
if (!defined('XOOPS_INSTALL')) {
	exit();
}

$wizard->setPage('langselect');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$lang = $_REQUEST['lang'];

	$languages = icms_core_Filesystem::getDirList("./language/");
	if (!in_array($lang, $languages)) {
		$lang = 'english';
	}
	setcookie('xo_install_lang', $lang, null, null, null);

	$wizard->redirectToPage('+1');
	exit();
}
$_SESSION = array();
$pageHasForm = true;
$pageHasHelp = true;
$title = LANGUAGE_SELECTION;
$content = "";

$languages = icms_core_Filesystem::getDirList("./language/");
foreach ($languages as $lang) {
	$sel = ($lang == $wizard->language)?' checked="checked"':'';
	$content .= "<div class=\"langselect\" style=\"text-decoration: none;\"><a href=\"javascript:void(0);\" style=\"text-decoration: none;\"><img src=\"../images/flags/$lang.gif\" alt=\"$lang\" /><br />$lang<br /> <input type=\"radio\" name=\"lang\" value=\"$lang\"$sel /></a></div>";
}
$content .= '<fieldset style="text-align: center;">';
$content .= '<div>' . ALTERNATE_LANGUAGE_MSG . '</div>';
$content .= '<div style="text-align: center; margin-top: 5px;"><a href="' . ALTERNATE_LANGUAGE_LNK_URL . '" target="_blank">' . ALTERNATE_LANGUAGE_LNK_MSG . '</a></div>';
$content .= '</fieldset>';

include 'install_tpl.php';
