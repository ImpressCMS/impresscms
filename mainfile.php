<?php
/**
 * All information in order to connect to database are going through here.
 *
 * Be careful if you are changing data's in this file.
 */
if (!defined("ICMS_MAINFILE_INCLUDED")) {
	define("ICMS_MAINFILE_INCLUDED", true);

	// (optional) Physical path to script that logs database queries.
	// Example: define('ICMS_LOGGING_HOOK', ICMS_ROOT_PATH . '/modules/foobar/logging_hook.php');
	define('ICMS_LOGGING_HOOK', '');

	foreach (array('GLOBALS', '_SESSION', 'HTTP_SESSION_VARS', '_GET', 'HTTP_GET_VARS', '_POST', 'HTTP_POST_VARS', '_COOKIE', 'HTTP_COOKIE_VARS', '_REQUEST', '_SERVER', 'HTTP_SERVER_VARS', '_ENV', 'HTTP_ENV_VARS', '_FILES', 'HTTP_POST_FILES', 'icmsConfig') as $bad_global) {
		if (isset($_REQUEST[$bad_global])) {
			http_response_code(400);
			exit();
		}
	}

	define('ICMS_GROUP_ADMIN', 1);
	define('ICMS_GROUP_USERS', 2);
	define('ICMS_GROUP_ANONYMOUS', 3);

	define('ICMS_ROOT_PATH', __DIR__);

	if (!isset($xoopsOption['nocommon'])) {
		include ICMS_ROOT_PATH . "/include/common.php";
	}
}