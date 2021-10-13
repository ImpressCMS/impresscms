<?php

use Aura\Session\SessionFactory;
use GuzzleHttp\Psr7\ServerRequest;
use ImpressCMS\Core\Controllers\DefaultController;
use ImpressCMS\Core\Database\DatabaseConnection;
use ImpressCMS\Core\Facades\Config;
use League\Route\Http\Exception as HttpException;
use League\Route\Router;
use Middlewares\BasePath;
use Narrowspark\HttpEmitter\SapiEmitter;

define('ICMS_PUBLIC_PATH', __DIR__);

// ImpressCMS is not installed yet.
if (is_dir('install') && strpos($_SERVER['REQUEST_URI'], '/install') === false) {
	try {
		include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'mainfile.php';
		/**
		 * @var DatabaseConnection $dbm
		 */
		$dbm = icms::getInstance()->get('db-connection-1');
		$isInstalled = $dbm->fetchCol('SELECT 1 FROM `' . $dbm->prefix('users') . '` LIMIT 1;') > 0;
	} catch (Throwable $exception) {
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
$router = icms::getInstance()->get('router');

$basePath = parse_url(
	env('URL'),
	PHP_URL_PATH
);
if ($basePath !== '/' && $basePath !== null) {
	$router->middleware(
		(new BasePath($basePath))->fixLocation()
	);
	if (substr($basePath, -1) !== '/') {
		$basePath .= '/';
	}
	if ($basePath[0] !== '/') {
		return '/' . $basePath;
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
} else {
	$request = ServerRequest::fromGlobals();
}
unset($basePath);

try {
	$response = $router->dispatch($request);
} catch (HttpException $httpException) {
	try {
		/**
		 * @var Config $configHandler
		 */
		$configHandler = icms::handler('icms_config');
		$mainConfig = $configHandler->getConfigsByCat(Config::CATEGORY_MAIN);
	} catch (Exception $exception) {
		$mainConfig = [];
	}

	$sessionName = ($mainConfig['use_mysession'] && $mainConfig['session_name']) ? $mainConfig['session_name'] : 'ICMSSESSION';
	icms::$session = (new SessionFactory())->newInstance(
		$request->getCookieParams()
	);

	$defController = new DefaultController();
	$_GET['e'] = $httpException->getStatusCode();
	$serverRequest = new ServerRequest(
		$request->getMethod(),
		$request->getUri(),
		$request->getHeaders(),
		$request->getBody(),
		$request->getProtocolVersion()
	);
	$response = $defController->getError(
		$serverRequest
			->withQueryParams($_GET)
			->withParsedBody($_POST)
			->withAttribute('session', icms::$session)
	);
}

/**
 * @var SapiEmitter $sapiEmitter
 */
$sapiEmitter = icms::getInstance()->get('sapi-emitter');
$sapiEmitter->emit($response);