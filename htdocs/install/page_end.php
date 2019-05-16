<?php
/**
 * Installer final page
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
 * @version		$Id: page_end.php 12389 2014-01-17 16:58:21Z skenow $
 */

/**
 *
 */
require_once 'common.inc.php';
if (!defined( 'XOOPS_INSTALL' ) )	exit();
include_once "../mainfile.php";
$success = isset($_GET['success'])?trim($_GET['success']):false;
if ($success) {
	if (is_dir(ICMS_ROOT_PATH.'/install')) {
		icms_core_Filesystem::deleteRecursive(ICMS_ROOT_PATH.'/install', true);
		header('Location: '.ICMS_URL.'/');
	}
	$_SESSION = array();
}

$wizard->setPage( 'end' );
$pageHasForm = false;
$content = "";
include "./language/$wizard->language/finish.php";

// destroy all the installation session
unset($_SESSION);
if(isset($_COOKIE[session_name()]))
{
	setcookie(session_name(), '', time()-60);
}
session_unset();
session_destroy();

include 'install_tpl.php';
?>