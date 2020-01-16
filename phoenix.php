<?php

/**
 * This file is used for configuring migrations service
 */

define('ICMS_MIGRATION_MODE', true);

require_once __DIR__ . '/mainfile.php';

/**
 * @var icms_db_Connection $databaseConnection
 */
$databaseConnection = \icms::getInstance()->get('db-connection-1');

/**
 * Finds module paths that contains migrations
 * (if module has folder called 'migrations' that means module has some migrations)
 */
$modulesMigrations = [];
foreach(icms_module_Handler::getAvailable() as $dirName) {
	$path = ICMS_MODULES_PATH . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . 'migrations';
	if (!file_exists($path) || !is_dir($path)) {
		continue;
	}
	$modulesMigrations['module/' . $dirName] = $path;
}

return [
	'log_table_name' => $databaseConnection->prefix('migrations'),
	'migration_dirs' => [
		 'core' => __DIR__ . '/migrations',
	] + $modulesMigrations,
	'environments' => [
		'local' => [
			'adapter' => str_replace('pdo.', '', env('DB_TYPE', 'mysql')),
			'host' => env('DB_HOST', '127.0.0.1'),
			'port' => (int)env('DB_PORT', 3306),
			'username' => env('DB_USER'),
			'password' => env('DB_PASS'),
			'db_name' => env('DB_NAME', 'impresscms'),
			'charset' => env('DB_CHARSET', 'utf8'),
		],
	],
	'default_environment ' => 'local',
];
