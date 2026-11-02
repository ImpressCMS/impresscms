<?php
/**
 * bin/test-db stage 2: system module, site settings and fixture accounts.
 *
 * Boots the freshly filled site through mainfile.php/common.php, updates the
 * system module as the installer's "modulesinstall" page does, sets the site
 * name and creates the `member` account (registered users group only).
 *
 * Usage: php install-system.php <mainfile> <json settings>
 */

[$mainfile, $settings] = [$argv[1], json_decode($argv[2], true, 512, JSON_THROW_ON_ERROR)];

$_SERVER += [
	'HTTP_HOST' => '127.0.0.1',
	'SERVER_NAME' => '127.0.0.1',
	'REQUEST_URI' => '/',
	'SCRIPT_NAME' => '/index.php',
	'QUERY_STRING' => '',
	'REMOTE_ADDR' => '127.0.0.1',
	// The legacy database layer only allows writes during POST requests
	// with a same-site referer (Icms\Core\Security::service()).
	'REQUEST_METHOD' => 'POST',
	'HTTP_REFERER' => 'http://127.0.0.1/',
];

require $mainfile;

include_once ICMS_ROOT_PATH . '/include/cp_functions.php';
include_once ICMS_ROOT_PATH . '/modules/system/include/update.php';
icms_loadLanguageFile('system', 'modulesadmin', true);

$module_handler = icms::handler('icms_module');
$system = $module_handler->getByDirname('system');
if (!is_object($system)) {
	fwrite(STDERR, "The system module row is missing.\n");
	exit(1);
}
ob_start();
$result = xoops_module_update_system($system);
ob_end_clean();
if ($result === false) {
	fwrite(STDERR, "xoops_module_update_system() failed.\n");
	exit(1);
}

$config_handler = icms::handler('icms_config');
foreach ($settings['config'] as $name => $value) {
	$items = $config_handler->getConfigs(new icms_db_criteria_Compo(new icms_db_criteria_Item('conf_name', $name)));
	foreach ($items as $item) {
		if ((int) $item->getVar('conf_modid') === 0) {
			$item->setConfValueForInput($value);
			$config_handler->insertConfig($item);
		}
	}
}

$member_handler = icms::handler('icms_member');
$member = $settings['member'];
$user = $member_handler->createUser();
$pwd = new icms_core_Password();
$user->setVar('uname', $member['uname'], true);
$user->setVar('login_name', $member['uname'], true);
$user->setVar('email', $member['email'], true);
$user->setVar('pass', $pwd->encryptPass($member['pass']), true);
$user->setVar('level', 1, true);
$user->setVar('user_regdate', time(), true);
$user->setVar('language', 'english', true);
$user->setVar('theme', 'iTheme', true);
$user->setVar('timezone_offset', '0.0', true);
$user->setVar('notify_method', 1, true);
$user->setVar('user_avatar', 'blank.gif', true);
// NOT NULL text columns without defaults (strict SQL mode)
foreach (['name', 'url', 'user_icq', 'user_from', 'user_sig', 'actkey', 'user_aim', 'user_yim', 'user_msnm', 'user_occ', 'bio', 'user_intrest'] as $column) {
	$user->setVar($column, '', true);
}
if (!$member_handler->insertUser($user, true)) {
	fwrite(STDERR, "Could not create the member account: " . implode(', ', $user->getErrors()) . ' ' . icms::$xoopsDB->error() . "\n");
	exit(1);
}
$member_handler->addUserToGroup(ICMS_GROUP_USERS, $user->getVar('uid'));

echo "System module updated; member account created.\n";
