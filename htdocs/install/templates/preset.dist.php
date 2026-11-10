<?php
/**
 * Installer preset.
 *
 * Copy this file to install/preset.php to pre-fill the installer. A page is skipped when the
 * preset holds every value it needs and they pass the page's normal validation; otherwise the
 * page is shown with the values already filled in. Without install/preset.php nothing changes.
 *
 * This file may contain passwords: remove it once the installation is done (the installer
 * deletes its own folder at the end). It prints nothing when requested through the web server.
 *
 * Every key is optional. Pages that are skipped when all their keys are present:
 *
 *   language           LANGUAGE
 *   locations          ROOT_PATH, TRUST_PATH, URL
 *   database server    DB_HOST, DB_USER, DB_PASS (may be '')  - optional: DB_TYPE, DB_PCONNECT
 *   database           DB_NAME                                - optional: DB_PREFIX, DB_SALT, DB_CHARSET, DB_COLLATION
 *   administrator      adminname, adminlogin_name, adminmail, adminpass
 *   modules            MODULES (array of module directories; [] installs none)
 *
 * The server requirements check is skipped when everything passes.
 */
defined('XOOPS_INSTALL') or exit();

return array(
	'LANGUAGE' => 'english',

	'ROOT_PATH' => '/var/www/html',
	'TRUST_PATH' => '/var/www/trust',
	'URL' => 'https://example.com',

	'DB_TYPE' => 'pdo.mysql',
	'DB_HOST' => 'localhost',
	'DB_USER' => '',
	'DB_PASS' => '',
	'DB_PCONNECT' => false,

	'DB_NAME' => '',
	// 'DB_PREFIX' => 'icms',
	// 'DB_SALT' => '',
	// 'DB_CHARSET' => 'utf8mb4',
	// 'DB_COLLATION' => '',

	'adminname' => 'Administrator',
	'adminlogin_name' => 'admin',
	'adminmail' => 'admin@example.com',
	'adminpass' => '',

	'MODULES' => array(),
);
