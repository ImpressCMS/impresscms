<?php
/**
 * Installer DB data insertion page
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

require_once 'common.inc.php';
if (!defined( 'XOOPS_INSTALL' ) )	exit();

$wizard->setPage( 'tablesfill' );
$pageHasForm = false;
$pageHasHelp = false;

$vars =& $_SESSION['settings'];

include_once "../../mainfile.php";
include_once './class/dbmanager.php';
$dbm = new db_manager();

if (!$dbm->isConnectable()) {
	$wizard->redirectToPage( 'dbsettings' );
	exit();
}

icms::$db = &$dbm->db;
icms::$xoopsDB = &$dbm->db;

$res = $dbm->query( "SELECT COUNT(*) FROM " . $dbm->db->prefix( "users" ) );
if (!$res) {
	$wizard->redirectToPage( 'dbsettings' );
	exit();
}
list ( $count ) = $dbm->db->fetchRow( $res );
$process = $count ? '' : 'insert';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (!$process) {
		$wizard->redirectToPage( '+0' );
		exit();
	}
	include_once './makedata.php';
	$cm = 'dummy';

	$wizard->loadLangFile( 'install2' );

	extract( $_SESSION['siteconfig'], EXTR_SKIP );
	$language = $wizard->language;

	$type = getenv('DB_TYPE');
	if (substr($type, 0, 4) == 'pdo.') {
		$driver = substr($type, 4);
	} else {
		$driver = $type;
	}
	$result = $dbm->queryFromFile('./sql/'. $driver .'.data.sql');
	$result = $dbm->queryFromFile('./language/' . $language . '/'. $driver . '.lang.data.sql');
	$group = make_groups( $dbm );
	$result = make_data( $dbm, $cm, $adminname, $adminlogin_name, $adminpass, $adminmail, $language, $group );
	$content = $dbm->report();
} else {
	$msg = $process ? READY_INSERT_DATA : DATA_ALREADY_INSERTED;
	$pageHasForm = $process ? true : false;

	$content = "<p class='x2-note'>$msg</p>";
}

include 'install_tpl.php';
