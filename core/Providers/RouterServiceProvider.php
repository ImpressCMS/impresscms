<?php

namespace ImpressCMS\Core\Providers;

use Defuse\Crypto\Exception\BadFormatException;
use Defuse\Crypto\Exception\EnvironmentIsBrokenException;
use Defuse\Crypto\Key;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Ellipse\Cookies\EncryptCookiesMiddleware;
use GuzzleHttp\Psr7\ServerRequest;
use Http\Factory\Guzzle\ResponseFactory;
use icms;
use ImpressCMS\Core\Facades\Config;
use ImpressCMS\Core\Middlewares\ChangeThemeMiddleware;
use ImpressCMS\Core\Middlewares\MultiLoginOnlineInfoUpdaterMiddleware;
use ImpressCMS\Core\Middlewares\SetSessionCookieConfigMiddleware;
use ImpressCMS\Core\Middlewares\SiteClosedMiddleware;
use ImpressCMS\Core\Middlewares\UserMiddleware;
use ImpressCMS\Core\Models\ModuleHandler;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Middlewares\AuraSession;
use Middlewares\BasePath;
use Middlewares\ClientIp;
use Middlewares\DeflateEncoder;
use Middlewares\Firewall;
use Middlewares\GzipEncoder;
use Psr\Http\Server\MiddlewareInterface;
use Sunrise\Http\Router\Loader\DescriptorLoader;
use Sunrise\Http\Router\Loader\LoaderInterface;
use Sunrise\Http\Router\Router;
use Tuupola\Middleware\ServerTimingMiddleware;

/**
 * Defines router
 *
 * @package ImpressCMS\Core\Providers
 */
class RouterServiceProvider extends AbstractServiceProvider
{
	/**
	 * @inheritdoc
	 */
	protected $provides = [
		'router',
		'request',
	];

	/**
	 * @inheritDoc
	 */
	public function register()
	{
		$this->leagueContainer->add('router', function () {
			$router = new Router();
			$router->load(
				$this->createDescriptorLoader()
			);
			$router->addMiddleware(
				...$this->getSystemMiddlewares(),
				...$this->getContainer()->get('middleware.global')
			);

			return $router;
		});

		$this->leagueContainer->add('request', function () {
			$basePath = parse_url(
				env('URL'),
				PHP_URL_PATH
			);
			if ($basePath !== '/' && $basePath !== null) {
				/**
				 * @var Router $router
				 */
				$router = $this->container->get('router');
				$router->addMiddleware(
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
			} else {
				$request = ServerRequest::fromGlobals();
			}

			return $request;
		});
	}

	/**
	 * Creates loader that defines controllers
	 *
	 * @return LoaderInterface
	 */
	protected function createDescriptorLoader(): LoaderInterface
	{
		AnnotationRegistry::registerLoader('class_exists');

		$loader = new DescriptorLoader();
		$loader->setContainer(
			$this->getContainer()
		);
		$loader->attachArray(
			$this->getControllerPaths()
		);
		$loader->setCache(
			$this->getContainer()->get('cache.simple')
		);
		$loader->setCacheKey('routes-descriptors');

		return $loader;
	}

	/**
	 * Get paths where controller exist
	 *
	 * @return string[]
	 */
	protected function getControllerPaths(): array
	{
		$paths = [
			ICMS_ROOT_PATH . '/core/Controllers'
		];

		foreach (ModuleHandler::getActive() as $moduleDir) {
			$path = ICMS_MODULES_PATH . '/' . $moduleDir . '/Controllers';
			if (is_dir($path)) {
				$paths[] = $path;
			}
		}

		return $paths;
	}

	/**
	 * Gets system middlewares
	 *
	 * @return MiddlewareInterface[]
	 *
	 * @throws BadFormatException
	 * @throws EnvironmentIsBrokenException
	 */
	protected function getSystemMiddlewares()
	{
		$middleware = [];

		if (env('LOGGING_ENABLED', false)) {
			$middleware[] = new ServerTimingMiddleware();
		}

		/**
		 * @var Config $configHandler
		 */
		$configHandler = icms::handler('icms_config');
		$configAll = $configHandler->getConfigsByCat([
			Config::CATEGORY_MAIN,
			Config::CATEGORY_PERSONA,
		]);
		$configMain = $configAll[Config::CATEGORY_MAIN];
		$configPersona = $configAll[Config::CATEGORY_PERSONA];

		if ($configMain['encrypt_cookies']) {
			$middleware[] = new EncryptCookiesMiddleware(
				Key::loadFromAsciiSafeString(
					env('APP_KEY')
				)
			);
		}

		$middleware[] = (new AuraSession())->name(
			($configMain['use_mysession'] && $configMain['session_name']) ? $configMain['session_name'] : 'ICMSSESSION'
		);

		$middleware[] = new SetSessionCookieConfigMiddleware(
			60 * $configMain['session_expire'],
			parse_url(ICMS_URL, PHP_URL_HOST),
			strpos(ICMS_URL, 'https') === 0
		);

		$middleware[] = new ChangeThemeMiddleware($configMain['theme_set_allowed']);
		$middleware[] = new UserMiddleware();

		if ($configPersona['multi_login']) {
			$middleware[] = new MultiLoginOnlineInfoUpdaterMiddleware();
		}

		if ($configMain['gzip_compression']) {
			$middleware[] = $this->container->get('\\' . GzipEncoder::class);
			$middleware[] = $this->container->get('\\' . DeflateEncoder::class);
		}

		if ($configMain['enable_badips']) {
			$middleware[] = new ClientIp();
			$middleware[] = (
			new Firewall(
				null,
				$this->container->get('\\' . ResponseFactory::class)
			)
			)
				->blacklist(
					$configMain['bad_ips']
				)
				->ipAttribute('client-ip');
		}

		if ($configMain['closesite']) {
			$middleware[] = new SiteClosedMiddleware(
				$configMain['closesite_okgrp'],
				$configMain['closesite_text'],
				$configMain['sitename'],
				$configMain['slogan']
			);
		}

		return $middleware;
	}
}