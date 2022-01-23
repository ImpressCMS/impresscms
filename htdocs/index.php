<?php

use ImpressCMS\Core\Database\DatabaseConnection;
use Narrowspark\HttpEmitter\SapiEmitter;
use Psr\Http\Message\ResponseFactoryInterface;
use Sunrise\Http\Router\Exception\MethodNotAllowedException;
use Sunrise\Http\Router\Exception\PageNotFoundException;
use Sunrise\Http\Router\Router;

define('ICMS_PUBLIC_PATH', __DIR__);

// ImpressCMS is not installed yet.
if (is_dir('install') && strpos($_SERVER['REQUEST_URI'], '/install') === false) {
	try {
		include_once dirname(__DIR__).DIRECTORY_SEPARATOR.'mainfile.php';
		/**
		 * @var DatabaseConnection $dbm
		 */
		$dbm = icms::getInstance()->get('db-connection-1');
		$isInstalled = $dbm->fetchCol('SELECT 1 FROM `'.$dbm->prefix('users').'` LIMIT 1;') > 0;

	} catch (Throwable $exception) {
		$isInstalled = false;
	}
	if (!$isInstalled) {
		header('Location: install/index.php');
		exit();
	}
} else {
	include_once dirname(__DIR__).DIRECTORY_SEPARATOR.'mainfile.php';
}

/**
 * @var Router $router
 */
$router = icms::getInstance()->get('router');

try {
	$response = $router->handle(
		icms::getInstance()->get('request')
	);
} catch (PageNotFoundException $e) {
	/**
	 * @var ResponseFactoryInterface $responseFactory
	 */
	$responseFactory = icms::getInstance()->get('response_factory');
	$response = $responseFactory->createResponse(404);
} catch (MethodNotAllowedException $e) {
	/**
	 * @var ResponseFactoryInterface $responseFactory
	 */
	$responseFactory = icms::getInstance()->get('response_factory');
	$response = $responseFactory
		->createResponse(405)
		->withHeader('Allow', $e->getJoinedAllowedMethods());
} catch (Throwable $httpException) {
	/**
	 * @var ResponseFactoryInterface $responseFactory
	 */
	$responseFactory = icms::getInstance()->get('response_factory');
	var_dump($httpException);
	die();
	$response = $responseFactory
		->createResponse(500, $httpException->getMessage());
}

/**
 * @var SapiEmitter $sapiEmitter
 */
$sapiEmitter = icms::getInstance()->get('sapi-emitter');
$sapiEmitter->emit($response);