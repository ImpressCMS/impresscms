<?php

/**
 * ImpressCMS Test Bootstrap
 *
 * This file initializes the testing environment for ImpressCMS.
 * It sets up necessary constants and autoloading without requiring
 * a full database connection or ImpressCMS installation.
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Tests
 * @since		1.5
 */

// Define the root path
define('ICMS_ROOT_PATH', dirname(__DIR__));

// Define testing environment
define('ICMS_TESTING', true);

// Define basic paths
define('ICMS_LIBRARIES_PATH', ICMS_ROOT_PATH . '/htdocs/libraries');
define('ICMS_CACHE_PATH', ICMS_ROOT_PATH . '/htdocs/cache');
define('ICMS_UPLOAD_PATH', ICMS_ROOT_PATH . '/htdocs/uploads');

// Load Composer autoloader
require_once ICMS_ROOT_PATH . '/vendor/autoload.php';

// Define database constants for testing (not actually used in unit tests)
if (!defined('XOOPS_DB_TYPE')) {
    define('XOOPS_DB_TYPE', 'pdo.mysql');
}
if (!defined('XOOPS_DB_HOST')) {
    define('XOOPS_DB_HOST', 'localhost');
}
if (!defined('XOOPS_DB_USER')) {
    define('XOOPS_DB_USER', 'test');
}
if (!defined('XOOPS_DB_PASS')) {
    define('XOOPS_DB_PASS', 'test');
}
if (!defined('XOOPS_DB_NAME')) {
    define('XOOPS_DB_NAME', 'test_db');
}
if (!defined('XOOPS_DB_PREFIX')) {
    define('XOOPS_DB_PREFIX', 'icms');
}
if (!defined('XOOPS_DB_CHARSET')) {
    define('XOOPS_DB_CHARSET', 'utf8mb4');
}
if (!defined('XOOPS_DB_PCONNECT')) {
    define('XOOPS_DB_PCONNECT', 0);
}

// Define URL constants for testing
if (!defined('ICMS_URL')) {
    define('ICMS_URL', 'http://localhost');
}

// Set error reporting for tests
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Set timezone to avoid warnings
if (!ini_get('date.timezone')) {
    date_default_timezone_set('UTC');
}

// Initialize the ImpressCMS autoloader if it exists
if (file_exists(ICMS_LIBRARIES_PATH . '/icms/Autoloader.php')) {
    require_once ICMS_LIBRARIES_PATH . '/icms/Autoloader.php';
    icms_Autoloader::setup();
}

// Mock the icms_Event class for testing if it doesn't exist
if (!class_exists('icms_Event', false)) {
    class icms_Event
    {
        public static function trigger($object, $event, $source = null, $data = [])
        {
            // Mock implementation - does nothing in tests
            return true;
        }
        
        public static function attach($object, $event, $callback)
        {
            // Mock implementation - does nothing in tests
            return true;
        }
    }
}

// Mock the icms class for testing if it doesn't exist
if (!class_exists('icms', false)) {
    class icms
    {
        public static $db = null;
        public static $xoopsDB = null;
        public static $logger = null;
        public static $config = [];
        
        public static function getInstance()
        {
            static $instance = null;
            if ($instance === null) {
                $instance = new self();
            }
            return $instance;
        }
    }
}

// Mock the icms_core_Logger class for testing if it doesn't exist
if (!class_exists('icms_core_Logger', false)) {
    class icms_core_Logger
    {
        public static function instance()
        {
            static $instance = null;
            if ($instance === null) {
                $instance = new self();
            }
            return $instance;
        }
        
        public function addQuery($sql, $error = null, $errno = null)
        {
            // Mock implementation
        }
        
        public function addExtra($name, $msg)
        {
            // Mock implementation
        }
    }
}

echo "\n";
echo "========================================\n";
echo "ImpressCMS Test Suite\n";
echo "========================================\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Root Path: " . ICMS_ROOT_PATH . "\n";
echo "Testing Mode: Enabled\n";
echo "========================================\n";
echo "\n";

