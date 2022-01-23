<?php
/**
 * Installer tables creation page
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
 */
/**
 *
 */
require_once 'common.inc.php';
if (!defined('XOOPS_INSTALL')) {
	exit();
}

include_once '../../mainfile.php';

icms_core_Filesystem::chmod('../.env', 0444);
icms_core_Filesystem::chmod(ICMS_ROOT_PATH.'/modules', 0777);
icms_core_Filesystem::chmod(ICMS_ROOT_PATH.'/modules', 0755);
$wizard->setPage('tablescreate');
$pageHasForm = true;
$pageHasHelp = false;

$wizard->redirectToPage('+1');
