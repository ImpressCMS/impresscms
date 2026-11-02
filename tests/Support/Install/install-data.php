<?php
/**
 * bin/test-db stage 1: tables and installer data.
 *
 * Runs the same steps as the web installer's "tablescreate" and "tablesfill"
 * pages: structure SQL, data SQL, English language data, make_groups() and
 * make_data(). Runs in its own process with the direct-deployment (htdocs/)
 * autoloader, exactly as the installer does.
 *
 * Usage: php install-data.php <mainfile> <json fixture>
 */

[$mainfile, $fixture] = [$argv[1], json_decode($argv[2], true, 512, JSON_THROW_ON_ERROR)];

$xoopsOption['nocommon'] = true;
define('XOOPS_INSTALL', 1);
require $mainfile;

chdir(ICMS_ROOT_PATH . '/install');
require ICMS_ROOT_PATH . '/vendor/autoload.php';
include_once ICMS_ROOT_PATH . '/include/version.php';
include_once ICMS_ROOT_PATH . '/include/functions.php';
require_once './include/functions.php';

include './language/english/install.php';
include './language/english/install2.php';
include_once './class/dbmanager.php';

$dbm = new db_manager();
if (!$dbm->isConnectable()) {
	fwrite(STDERR, "Cannot connect to the test database.\n");
	exit(1);
}

$dbm->queryFromFile('./sql/mysql.structure.sql');
$dbm->queryFromFile('./sql/mysql.data.sql');
$dbm->queryFromFile('./language/english/mysql.lang.data.sql');

include_once './makedata.php';
$cm = 'dummy';
$groups = make_groups($dbm);
if ($groups === false) {
	fwrite(STDERR, "make_groups() failed.\n");
	exit(1);
}
make_data(
	$dbm,
	$cm,
	$fixture['uname'],
	$fixture['login'],
	$fixture['pass'],
	$fixture['email'],
	'english',
	$groups
);

if (!empty($dbm->f_tables)) {
	fwrite(STDERR, "Installer queries failed:\n" . print_r($dbm->f_tables, true));
	exit(1);
}
echo "Tables created and filled.\n";
