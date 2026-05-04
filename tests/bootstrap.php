<?php
/**
 * Minimal bootstrap for PHPUnit tests on ImpressCMS base classes.
 * Designed for testing /htdocs/libraries/icms without loading full CMS.
 */

declare(strict_types=1);

// ------------------------------------------------------------
// 1. Define core constants expected by ImpressCMS
// ------------------------------------------------------------
if (!defined('ICMS_ROOT_PATH')) {
	define('ICMS_ROOT_PATH', dirname(__DIR__) . '/htdocs');
}
if (!defined('ICMS_LIBRARIES_PATH')) {
	define('ICMS_LIBRARIES_PATH', ICMS_ROOT_PATH . '/libraries');
}
if (!defined('ICMS_TRUST_PATH')) {
	define('ICMS_TRUST_PATH', ICMS_ROOT_PATH . '/trust_path');
}
if (!defined('ICMS_URL')) {
	define('ICMS_URL', 'http://localhost');
}
if (!defined('ICMS_VERSION_NAME')) {
	define('ICMS_VERSION_NAME', 'test-suite');
}
if (!defined('ICMS_DB_PREFIX')) {
	define('ICMS_DB_PREFIX', 'icms_test_');
}

// Language and error constants used by core object/data filter classes.
if (!defined('_CHARSET')) {
	define('_CHARSET', 'utf-8');
}
if (!defined('_XOBJ_ERR_REQUIRED')) {
	define('_XOBJ_ERR_REQUIRED', '%s is required');
}
if (!defined('_XOBJ_ERR_SHORTERTHAN')) {
	define('_XOBJ_ERR_SHORTERTHAN', '%s must be shorter than %d characters');
}
if (!defined('_CORE_DB_INVALIDEMAIL')) {
	define('_CORE_DB_INVALIDEMAIL', 'Invalid email');
}
if (!defined('_ERROR')) {
	define('_ERROR', 'Error');
}
if (!defined('_NONE')) {
	define('_NONE', 'None');
}
if (!defined('_CO_ICMS_SUBMIT')) {
	define('_CO_ICMS_SUBMIT', 'Submit');
}
if (!defined('ICMS_CONF_CENSOR')) {
	define('ICMS_CONF_CENSOR', 2);
}

// Disable error suppression in legacy code
error_reporting(E_ALL);
ini_set('display_errors', '1');

// ------------------------------------------------------------
// 2. Composer autoloader (for PHPUnit + your own PSR-4 classes)
// ------------------------------------------------------------
require_once __DIR__ . '/../vendor/autoload.php';

// ------------------------------------------------------------
// 3. Legacy ImpressCMS autoloader for icms_* classes
// ------------------------------------------------------------
spl_autoload_register(function ($class) {
	// Only handle legacy ImpressCMS classes
	if (strpos($class, 'icms_') !== 0) {
		return;
	}

	// Convert icms_ipf_Object → /htdocs/libraries/icms/ipf/Object.php
	$path = str_replace('_', '/', $class) . '.php';
	$file = ICMS_LIBRARIES_PATH . '/' . $path;

	if (file_exists($file)) {
		require_once $file;
	}
});

// ------------------------------------------------------------
// 4. Provide a mock database layer
// ------------------------------------------------------------
class icms_db_legacy_Database
{
	public function query($sql)
	{
		// No real DB — return predictable dummy result
		return true;
	}

	public function fetchArray($result)
	{
		return [];
	}

	public function escape($value)
	{
		return addslashes((string) $value);
	}
}

// Global DB instance expected by legacy code
$GLOBALS['xoopsDB'] = new icms_db_legacy_Database();

// ------------------------------------------------------------
// 5. Mock some global ImpressCMS functions
// ------------------------------------------------------------
function icms_loadLanguageFile($name, $module = null)
{
	// No-op for tests
	return true;
}

function icms_getConfig($key)
{
	return null;
}

if (!class_exists('icms')) {
	class icms
	{
		public static $config;
	}
}

if (!isset(icms::$config)) {
	icms::$config = new class {
		public function getConfigsByCat($category)
		{
			return [
				'censor_enable' => false,
				'censor_replace' => '*',
				'censor_words' => [],
			];
		}
	};
}

if (!function_exists('icms_currency')) {
	function icms_currency($value)
	{
		return (float) $value;
	}
}

// ------------------------------------------------------------
// 6. Ensure timezone and locale are stable
// ------------------------------------------------------------
date_default_timezone_set('UTC');
setlocale(LC_ALL, 'C');

// Bootstrap complete
