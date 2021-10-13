<?php
/**
 * Installer DB data insertion page
 *
 * See the enclosed file license.txt for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright    The XOOPS project http://www.xoops.org/
 * @license      http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package        installer
 * @since        Xoops 2.3.0
 * @author        Haruki Setoyama  <haruki@planewave.org>
 * @author        Kazumi Ono <webmaster@myweb.ne.jp>
 * @author        Skalpa Keo <skalpa@xoops.org>
 */

use Phoenix\Command\InitCommand;
use Phoenix\Command\MigrateCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

require_once 'common.inc.php';
if (!defined('XOOPS_INSTALL')) {
	exit();
}

$wizard->setPage('tablesfill');
$pageHasForm = false;
$pageHasHelp = false;

$vars = & $_SESSION['settings'];

include_once "../../mainfile.php";
include_once './class/dbmanager.php';
$dbm = new db_manager();

if (!$dbm->isConnectable()) {
	$wizard->redirectToPage('dbsettings');
	exit();
}

/**
 * @var icms_db_Connection $db
 */
$db = icms::getInstance()->get('db');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$cm = 'dummy';

	$wizard->loadLangFile('install2');

	extract($_SESSION['siteconfig'], EXTR_SKIP);
	$language = $wizard->language;

	$type = env('DB_TYPE');
	if (substr($type, 0, 4) == 'pdo.') {
		$driver = substr($type, 4);
	} else {
		$driver = $type;
	}

	if (!$adminname || !$adminlogin_name || !$adminpass || !$adminmail || !$language) {
		$wizard->redirectToPage('-1');
		exit();
	}

	putenv('INSTALL_ADMIN_EMAIL=' . $adminmail);
	putenv('INSTALL_ADMIN_NAME=' . $adminname);
	putenv('INSTALL_ADMIN_PASS=' . $adminpass);
	putenv('INSTALL_ADMIN_LOGIN=' . $adminlogin_name);
	putenv('INSTALL_LANGUAGE=' . $language);
	$symfonyConsoleApplication = new Application('icms-installer');
	$symfonyConsoleApplication->setAutoExit(false);
	$symfonyConsoleApplication->add(new InitCommand());
	$symfonyConsoleApplication->add(new MigrateCommand());
	$output = new BufferedOutput();
	/** @noinspection NotOptimalIfConditionsInspection */
	if (($symfonyConsoleApplication->run(new ArrayInput([
				'command' => 'init',
				'-v' => true,
				'--config_type' => 'php',
				'--config' => dirname(dirname(__DIR__)) . '/phoenix.php',
			]), $output) > 0) && (strrpos($content = $output->fetch(), 'Phoenix was already initialized') === false)) {

		$content = nl2br($content);
		$pageHasForm = true;
		include 'install_tpl.php';

		//$wizard->redirectToPage('dbsettings');
		exit();
	}
	$symfonyConsoleApplication->run(new ArrayInput([
		'command' => 'migrate',
		'--dir' => ['core'],
		'--config_type' => 'php',
		'--config' => dirname(dirname(__DIR__)) . '/phoenix.php',
	]), $output);
	$content = nl2br($output->fetch());
} else {
	$msg = READY_INSERT_DATA;
	$pageHasForm = true;

	$content = "<p class='x2-note'>$msg</p>";
}

include 'install_tpl.php';
