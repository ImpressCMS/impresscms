<?php
/**
 * All information in order to connect to database are going through here.
 *
 * Be careful if you are changing data's in this file.
 */
if (!defined("ICMS_MAINFILE_INCLUDED")) {
	define("ICMS_MAINFILE_INCLUDED", true);

	define('ICMS_ROOT_PATH', __DIR__);

	// Including libs with composer
	include_once ICMS_ROOT_PATH . "/vendor/autoload.php";

	// Loads environment data
	\Dotenv\Dotenv::create(__DIR__)->safeLoad();

	// (optional) Physical path to script that logs database queries.
	// Example: define('ICMS_LOGGING_HOOK', ICMS_ROOT_PATH . '/modules/foobar/logging_hook.php');
	define('ICMS_LOGGING_HOOK', env('LOGGING_HOOK'));

	define('ICMS_GROUP_ADMIN', 1);
	define('ICMS_GROUP_USERS', 2);
	define('ICMS_GROUP_ANONYMOUS', 3);

	define('ICMS_URL', env('URL'));

	if (!isset($xoopsOption['nocommon'])) {
		include ICMS_ROOT_PATH . "/include/common.php";
	}
}