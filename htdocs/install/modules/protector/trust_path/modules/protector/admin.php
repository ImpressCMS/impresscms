<?php
$mytrustdirname = basename(__DIR__);
$mytrustdirpath = __DIR__;

// environment
$module_handler = icms::handler('icms_module');
$xoopsModule = $module_handler->getByDirname($mydirname);
$config_handler = icms::handler('icms_config');
$xoopsModuleConfig = &$config_handler->getConfigsByCat(0, $xoopsModule->getVar('mid'));

// check permission of 'module_admin' of this module
$moduleperm_handler = icms::handler('icms_member_groupperm');
if (!is_object(@icms::$user) || !$moduleperm_handler->checkRight('module_admin', $xoopsModule->getVar('mid'), icms::$user->getGroups())) die('only admin can access this area');

$xoopsOption['pagetype'] = 'admin';
require ICMS_ROOT_PATH . '/include/cp_functions.php';

// language files (admin.php)
$language = empty($icmsConfig['language']) ? 'english' : $icmsConfig['language'];
if (file_exists("$mydirpath/language/$language/admin.php")) {
	// user customized language file
	include_once "$mydirpath/language/$language/admin.php";
} else if (file_exists("$mytrustdirpath/language/$language/admin.php")) {
	// default language file
	include_once "$mytrustdirpath/language/$language/admin.php";
} else {
	// fallback english
	include_once "$mytrustdirpath/language/english/admin.php";
}

// language files (main.php)
$language = empty($icmsConfig['language']) ? 'english' : $icmsConfig['language'];
if (file_exists("$mydirpath/language/$language/main.php")) {
	// user customized language file
	include_once "$mydirpath/language/$language/main.php";
} else if (file_exists("$mytrustdirpath/language/$language/main.php")) {
	// default language file
	include_once "$mytrustdirpath/language/$language/main.php";
} else {
	// fallback english
	include_once "$mytrustdirpath/language/english/main.php";
}

// fork each pages of this module
$page = preg_replace('/[^a-zA-Z0-9_-]/', '', @$_GET['page']);

if (file_exists("$mytrustdirpath/admin/$page.php")) {
	include "$mytrustdirpath/admin/$page.php";
} else if (file_exists("$mytrustdirpath/admin/index.php")) {
	include "$mytrustdirpath/admin/index.php";
} else {
	die('wrong request');
}
