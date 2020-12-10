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

use GuzzleHttp\Psr7\ServerRequest;
use ImpressCMS\Core\Controllers\DefaultController;
use League\Route\Http\Exception as HttpException;
use League\Route\Router;

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
 * @var Router $router
 */
$router = \icms::getInstance()->get('router');

$basePath = parse_url(
	env('URL'),
	PHP_URL_PATH
);
if ($basePath !== '/' && $basePath !== null) {
	$router->middleware(
		(new \Middlewares\BasePath($basePath))->fixLocation()
	);
	if (substr($basePath, -1) !== '/') {
		$basePath .= '/';
	}
	if ($basePath[0] !== '/') {
		return '/'.$basePath;
	}
	$request = ServerRequest::fromGlobals();
	$uri = $request->getUri();
	$path = $uri->getPath();
	if (strpos($path, $basePath) === 0) {
		$path = substr($path, strlen($basePath)) ?: '';
		if ($path === '') {
			$path = '/';
		}
		$request = $request->withUri(
			$uri->withPath($path)
		);
	}
	unset($path, $uri);
}
unset($basePath);

try {
	$response = $router->dispatch($request);
} catch (HttpException $httpException) {
	$defController = new DefaultController();
	$_GET['e'] = $httpException->getStatusCode();
	$response = $defController->getError(
		(new ServerRequest(
			$request->getMethod(),
			$request->getUri(),
			$request->getHeaders(),
			$request->getBody(),
			$request->getProtocolVersion()
		))
			->withQueryParams($_GET)
			->withParsedBody($_POST)
	);
}

\icms::getInstance()->get('sapi-emitter')->emit($response);