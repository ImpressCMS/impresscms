<?php
/**
 * bin/test-db stage 3: fixture data for page tests.
 *
 * - three mimetypes with `use_extension` groups (the mimetype admin page)
 * - administrators of the system module that are not webmasters:
 *   `mediaadmin` holds the `system_admin` right for the user ranks only,
 *   `mimetypeadmin` holds it for the mimetypes
 *
 * Usage: php install-fixtures.php <mainfile> <json settings>
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
include_once ICMS_ROOT_PATH . '/modules/system/constants.php';

$fail = static function (string $message): void {
	fwrite(STDERR, $message . ' ' . icms::$xoopsDB->error() . "\n");
	exit(1);
};

$system = icms::handler('icms_module')->getByDirname('system');
$systemId = (int) $system->getVar('mid');

// -- Mimetypes ----------------------------------------------------------------
$mimetypes = icms_getModuleHandler('mimetype', 'system');
$permissions = new icms_ipf_permission_Handler($mimetypes);
foreach ($settings['mimetypes'] as $row) {
	$mimetype = $mimetypes->create();
	foreach (['extension', 'types', 'name'] as $key) {
		$mimetype->setVar($key, $row[$key]);
	}
	$mimetype->setVar('dirname', $row['dirname']);
	if (!$mimetypes->insert($mimetype, true)) {
		$fail('Could not create mimetype ' . $row['extension'] . ': ' . implode(', ', $mimetype->getErrors()));
	}
	$permissions->saveItem_Permissions($row['use_extension'], (int) $mimetype->id(), 'use_extension');
}

// -- System module administrators without webmaster rights ------------------------
$member_handler = icms::handler('icms_member');
$gperm_handler = icms::handler('icms_member_groupperm');
foreach ($settings['admins'] as $admin) {
	$group = $member_handler->createGroup();
	$group->setVar('name', $admin['group']);
	$group->setVar('description', '');
	$group->setVar('group_type', 'User');
	if (!$member_handler->insertGroup($group)) {
		$fail('Could not create group ' . $admin['group'] . '.');
	}
	$groupId = (int) $group->getVar('groupid');
	$gperm_handler->addRight('module_admin', $systemId, $groupId, 1);
	$gperm_handler->addRight('module_read', $systemId, $groupId, 1);
	foreach ($admin['system_admin'] as $category) {
		$gperm_handler->addRight('system_admin', (int) constant($category), $groupId, $systemId);
	}

	$user = $member_handler->createUser();
	$pwd = new icms_core_Password();
	$user->setVar('uname', $admin['uname'], true);
	$user->setVar('login_name', $admin['uname'], true);
	$user->setVar('email', $admin['email'], true);
	$user->setVar('pass', $pwd->encryptPass($admin['pass']), true);
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
		$fail('Could not create the ' . $admin['uname'] . ' account: ' . implode(', ', $user->getErrors()));
	}
	$member_handler->addUserToGroup(ICMS_GROUP_USERS, $user->getVar('uid'));
	$member_handler->addUserToGroup($groupId, $user->getVar('uid'));
}

echo "Fixtures created: " . count($settings['mimetypes']) . " mimetypes, " . count($settings['admins']) . " system administrators.\n";
