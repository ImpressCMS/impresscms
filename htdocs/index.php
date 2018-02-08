<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

/**
 * Site index aka home page.
 * redirects to installation, if ImpressCMS is not installed yet
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ImpressCMS\Core
 * @since	XOOPS
 * @author	skalpa <psk@psykaos.net>
 * @author	Sina Asghari(aka stranger) <pesian_stranger@users.sourceforge.net>
 **/

/** mainfile is required, if it doesn't exist - installation is needed */

$requested_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$path = preg_replace('/[^a-zA-Z0-9\/]/', '', $requested_path);

/**
 * @todo Remove this proxy once we migrate everything to normal assets system
 */
if (
	!empty($requested_path) &&
	file_exists($full_path = dirname(__DIR__) . DIRECTORY_SEPARATOR . $requested_path) &&
	($ext = pathinfo($full_path, PATHINFO_EXTENSION)) != 'php'
) {
	if ($requested_path[0] == '.') { // protect hidden files
		$_REQUEST['e'] = 403;
		http_response_code(403);
		include 'error.php';
		exit();
	}
	switch ($ext) {
		case 'css':
			$mimetype = 'text/css';
		break;
		case 'js':
			$mimetype = 'text/javascript';
		break;
		default:
			$mimetype = mime_content_type($full_path);
	}
	header('Content-Type: ' . $mimetype);
	readfile($full_path);
	exit(0);
}

foreach (array('GLOBALS', '_SESSION', 'HTTP_SESSION_VARS', '_GET', 'HTTP_GET_VARS', '_POST', 'HTTP_POST_VARS', '_COOKIE', 'HTTP_COOKIE_VARS', '_REQUEST', '_SERVER', 'HTTP_SERVER_VARS', '_ENV', 'HTTP_ENV_VARS', '_FILES', 'HTTP_POST_FILES', 'icmsConfig') as $bad_global) {
	if (isset($_REQUEST[$bad_global])) {
		$_REQUEST['e'] = 400;
		http_response_code(400);
		include 'error.php';
		exit();
	}
}

// ImpressCMS is not installed yet.
if (is_dir('install') && strpos($_SERVER['REQUEST_URI'], '/install') === false) {
	header('Location: install/index.php');
	exit();
}

define('ICMS_PUBLIC_PATH', __DIR__);

include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'mainfile.php';

if (isset($ext)) {

	// for backward compatibility
	$_SERVER['SCRIPT_NAME'] = '/' . $requested_path;
	$_SERVER['PHP_SELF'] = $_SERVER['SCRIPT_NAME'];

	require $full_path;
} elseif (preg_match_all('|([^/]+)/([^/]+)/([^/]+)(.*)|', $path, $params, PREG_SET_ORDER) === 1) {
	\icms::$logger->disableRendering();
	list(, $module, $controller_name, $action, $params) = $params[0];
	$handler = new \icms_controller_Handler();
	try {
		$handler->exec(
			$module,
			$handler->type,
			$controller_name,
			strtolower($_SERVER['REQUEST_METHOD']) . ucfirst($action),
			$handler->parseParamsStringToArray($module, $controller_name, $params)
		);
	} catch (Exception $ex) {
		\icms::$response = new \icms_response_Error();
		\icms::$response->errorNo = 404;
		\icms::$response->render();
	}
} elseif (isset($_SERVER['REDIRECT_URL']) && ($_SERVER['REDIRECT_URL'] != '/')) {
	\icms::$response = new \icms_response_Error();
	\icms::$response->errorNo = 404;
	\icms::$response->render();
} else {
	$member_handler = \icms::handler('icms_member');
	$group = $member_handler->getUserBestGroup(
		(!empty(\icms::$user) && is_object(\icms::$user)) ? \icms::$user->uid : 0
	);

	// added failover to default startpage for the registered users group -- JULIAN EGELSTAFF Apr 3 2017
	$groups = (!empty(\icms::$user) && is_object(\icms::$user)) ? \icms::$user->getGroups() : array(ICMS_GROUP_ANONYMOUS);
	if(($icmsConfig['startpage'][$group] == "" OR $icmsConfig['startpage'][$group] == "--")
		AND in_array(ICMS_GROUP_USERS, $groups)
		AND $icmsConfig['startpage'][ICMS_GROUP_USERS] != ""
		AND $icmsConfig['startpage'][ICMS_GROUP_USERS] != "--") {
		$icmsConfig['startpage'] = $icmsConfig['startpage'][ICMS_GROUP_USERS];
	} else {
		$icmsConfig['startpage'] = $icmsConfig['startpage'][$group];
	}


	if (isset($icmsConfig['startpage']) && $icmsConfig['startpage'] != '' && $icmsConfig['startpage'] != '--') {
		$arr = explode('-', $icmsConfig['startpage']);
		if (count($arr) > 1) {
			$page_handler = \icms::handler('icms_data_page');
			$page = $page_handler->get($arr[1]);
			if (is_object($page)) {
				header('Location: ' . $page->getURL());
			} else {
				$icmsConfig['startpage'] = '--';
				\icms::$response = new \icms_response_DefaultEmptyPage();
				\icms::$response->render();
			}
		} else {
			header('Location: ' . ICMS_MODULES_URL . '/' . $icmsConfig['startpage'] . '/');
		}
		exit();
	} else {
		\icms::$response = new \icms_response_DefaultEmptyPage();
		\icms::$response->render();
	}
}
