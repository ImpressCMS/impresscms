<?php
/**#@+
 * Creating ICMS specific constants
 */
define('ICMS_PLUGINS_PATH', ICMS_ROOT_PATH . '/plugins');
define('ICMS_PLUGINS_URL', ICMS_URL . '/plugins');
define('ICMS_PRELOAD_PATH', ICMS_PLUGINS_PATH . '/preloads');
// ImpressCMS Modules path & url
define('ICMS_MODULES_PATH', ICMS_ROOT_PATH . '/modules');
define('ICMS_MODULES_URL', ICMS_URL . '/modules');
/**#@-*/

// ################# Creation of the ImpressCMS Libraries ##############
/**
 * @todo The definition of the library path needs to be in mainfile
 */
// ImpressCMS Third Party Libraries folder
define('ICMS_LIBRARIES_PATH', ICMS_ROOT_PATH . '/libraries');
define('ICMS_LIBRARIES_URL', ICMS_URL . '/libraries');
/**#@+
 * Constants
 */
define('XOOPS_SIDEBLOCK_LEFT', 1);
define('XOOPS_SIDEBLOCK_RIGHT', 2);
define('XOOPS_SIDEBLOCK_BOTH', -2);
define('XOOPS_CENTERBLOCK_LEFT', 3);
define('XOOPS_CENTERBLOCK_RIGHT', 5);
define('XOOPS_CENTERBLOCK_CENTER', 4);
define('XOOPS_CENTERBLOCK_ALL', -6);
define('XOOPS_CENTERBLOCK_BOTTOMLEFT', 6);
define('XOOPS_CENTERBLOCK_BOTTOMRIGHT', 8);
define('XOOPS_CENTERBLOCK_BOTTOM', 7);

define('XOOPS_BLOCK_INVISIBLE', 0);
define('XOOPS_BLOCK_VISIBLE', 1);
define('XOOPS_MATCH_START', 0);
define('XOOPS_MATCH_END', 1);
define('XOOPS_MATCH_EQUAL', 2);
define('XOOPS_MATCH_CONTAIN', 3);

define('ICMS_INCLUDE_PATH', ICMS_ROOT_PATH . '/include');
define('ICMS_INCLUDE_URL', ICMS_ROOT_PATH . '/include');
define('ICMS_UPLOAD_PATH', ICMS_PUBLIC_PATH . '/uploads');
define('ICMS_UPLOAD_URL', ICMS_URL . '/uploads');
define('ICMS_THEME_PATH', ICMS_PUBLIC_PATH . '/themes');
define('ICMS_THEME_URL', ICMS_URL . '/themes');
define('ICMS_STORAGE_PATH', ICMS_ROOT_PATH . DIRECTORY_SEPARATOR . 'storage');
define('ICMS_CACHE_PATH', ICMS_STORAGE_PATH . DIRECTORY_SEPARATOR . 'cache');
define('ICMS_PURIFIER_CACHE', ICMS_STORAGE_PATH . DIRECTORY_SEPARATOR . 'htmlpurifier');
define('ICMS_COMPILE_PATH', ICMS_STORAGE_PATH . DIRECTORY_SEPARATOR . 'templates_c');
define('ICMS_IMAGES_URL', ICMS_URL . '/images');
define('ICMS_EDITOR_PATH', ICMS_PUBLIC_PATH . '/editors');
define('ICMS_EDITOR_URL', ICMS_URL . '/editors');
define('ICMS_IMANAGER_FOLDER_PATH', ICMS_UPLOAD_PATH . '/imagemanager');
define('ICMS_IMANAGER_FOLDER_URL', ICMS_UPLOAD_URL . '/imagemanager');
/**#@-*/

/**
 * @todo make this $icms_images_setname as an option in preferences...
 */
$icms_images_setname = 'kfaenza';
define('ICMS_IMAGES_SET_URL', ICMS_IMAGES_URL . '/' . $icms_images_setname);

define('SMARTY_DIR', ICMS_LIBRARIES_PATH . '/smarty/');

if (!defined('XOOPS_XMLRPC')) {
	define('XOOPS_DB_CHKREF', 1);
} else {
	define('XOOPS_DB_CHKREF', 0);
}
