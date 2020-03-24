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
 * @copyright    http://www.xoops.org/ The XOOPS Project
 * @copyright    http://www.impresscms.org/ The ImpressCMS Project
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package    ImpressCMS\Core
 * @since    XOOPS
 * @author    skalpa <psk@psykaos.net>
 * @author    Sina Asghari(aka stranger) <pesian_stranger@users.sourceforge.net>
 */

/** mainfile is required, if it doesn't exist - installation is needed */

define('ICMS_PUBLIC_PATH', __DIR__);

// ImpressCMS is not installed yet.
if (is_dir('install') && strpos($_SERVER['REQUEST_URI'], '/install') === false) {
	try {
		include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'mainfile.php';
		/**
		 * @var \icms_db_Connection $dbm
		 */
		$dbm = \icms::getInstance()->get('db-connection-1');
		$isInstalled = $dbm->fetchCol('SELECT COUNT(*) FROM `' . $dbm->prefix('users') . '`;') > 0;
	} catch (\Exception $exception) {
		$isInstalled = false;
	}
	if (!$isInstalled) {
		header('Location: install/index.php');
		exit();
	}
} else {
	include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'mainfile.php';
}

/**
 * @var \League\Route\Router $router
 */
$router = \icms::getInstance()->get('router');

$request = \GuzzleHttp\Psr7\ServerRequest::fromGlobals();
try {
	$response = $router->dispatch($request);
} catch (\Exception $exception) {
	$_REQUEST['e'] = 404;
	include __DIR__ . '/error.php';
}

\icms::getInstance()->get('sapi-emitter')->emit($response);

/*
$member_handler = \icms::handler('icms_member');
$group = $member_handler->getUserBestGroup(
	(!empty(\icms::$user) && is_object(\icms::$user)) ? \icms::$user->uid : 0
);

// added failover to default startpage for the registered users group -- JULIAN EGELSTAFF Apr 3 2017
$groups = (!empty(\icms::$user) && is_object(\icms::$user)) ? \icms::$user->getGroups() : array(ICMS_GROUP_ANONYMOUS);
if (($icmsConfig['startpage'][$group] == "" or $icmsConfig['startpage'][$group] == "--")
	and in_array(ICMS_GROUP_USERS, $groups)
	and $icmsConfig['startpage'][ICMS_GROUP_USERS] != ""
	and $icmsConfig['startpage'][ICMS_GROUP_USERS] != "--") {
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
}*/