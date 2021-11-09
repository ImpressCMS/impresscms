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
use ImpressCMS\Core\Controllers\LegacyController;
use ImpressCMS\Core\Facades\Config;
use ImpressCMS\Core\Middlewares\ChangeThemeMiddleware;
use ImpressCMS\Core\Middlewares\MultiLoginOnlineInfoUpdaterMiddleware;
use ImpressCMS\Core\Middlewares\SetSessionCookieConfigMiddleware;
use ImpressCMS\Core\Middlewares\SiteClosedMiddleware;
use ImpressCMS\Core\Middlewares\UserMiddleware;
use ImpressCMS\Core\Models\ModuleHandler;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use League\Flysystem\StorageAttributes;
use Middlewares\AuraSession;
use Middlewares\BasePath;
use Middlewares\ClientIp;
use Middlewares\DeflateEncoder;
use Middlewares\Firewall;
use Middlewares\GzipEncoder;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Server\MiddlewareInterface;
use Sunrise\Http\Router\Loader\DescriptorLoader;
use Sunrise\Http\Router\Loader\LoaderInterface;
use Sunrise\Http\Router\RouteCollector;
use Sunrise\Http\Router\RouteInterface;
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
			static $router = null;

			if ($router === null) {
				$router = new Router();
				$router->load(
					$this->createDescriptorLoader()
				);
				$router->addRoute(
					...$this->getOldStyleRoutes()
				);
				$router->addMiddleware(
					...$this->getSystemMiddlewares(),
					...$this->getContainer()->get('middleware.global')
				);
			}

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


	private function createPathRegExpString(Filesystem $filesystem, string $path, array $excludedExtensions = [], array $excludedFilenames = [])
	{
		$parts = [];
		/**
		 * @var StorageAttributes $item
		 */
		foreach ($filesystem->listContents($path, false) as $item) {
			var_dump($item->path());
			die();
			if ($item->isDir()) {

				continue;
			}

			if (
				($file['type'] !== 'file') ||
				($file['basename'][0] === '.') ||
				in_array($file['basename'], $excludedFilenames, true) ||
				in_array(strtolower($file['extension']), $excludedExtensions, true)
			) {
				continue;
			}
			$parts[] = preg_quote($file['path'], '/');
		}

		if (empty($parts)) {
			return null;
		}

		return (count($parts) > 1) ? ('(' . implode('|', $parts) . ')') : $parts[0];
	}

	/**
	 * Finds accessible items from path
	 *
	 * @param Filesystem $filesystem Filesystem instance
	 * @param string $prefix Prefix for each included item
	 * @param string $path Path where to look in this filesystem
	 * @param bool $deep Recursive lookup?
	 * @param string[] $excludedBasenames Filenames that will be excluded
	 * @param string[] $exludedExtensions Extensions that will be ignored
	 *
	 * @return string[]
	 *
	 * @throws FilesystemException
	 */
	protected function findAccessibleItemsFromPath(Filesystem $filesystem, string $prefix, string $path, bool $deep = true, array $excludedBasenames = [], array $exludedExtensions = [], ?callable $customItemFilterCallback = null): array
	{
		$paths = [];

		/**
		 * @var FileAttributes $item
		 */
		foreach ($filesystem->listContents($path, $deep) as $item) {
			if (!$item->isFile()) {
				continue;
			}

			$path = $item->path();
			$basename = basename($path);

			if (in_array($basename, $excludedBasenames, true)) {
				continue;
			}

			$ext = pathinfo($basename, PATHINFO_EXTENSION);

			if (in_array(strtolower($ext), $exludedExtensions, true)) {
				continue;
			}

			$pathsParts = explode('/', $path);
			foreach ($pathsParts as $pathPart) {
				if ($pathPart === '') {
					continue;
				}
				if ($pathPart[0] === '.') {
					continue 2;
				}
			}
			if (($customItemFilterCallback !== null) && !$customItemFilterCallback($pathsParts)) {
				continue;
			}

			$paths[] = $prefix . '/' . $path;
		}

		return $paths;
	}

	/**
	 * Get regexp for legacy routes
	 *
	 * @return string
	 *
	 * @throws FilesystemException
	 */
	protected function getLegacyRegexp(): string
	{
		$ignoredExts = ['html', 'htm', 'md', 'txt', 'yaml', 'yml', 'xml', 'json', 'lock'];

		$paths = $this->findAccessibleItemsFromPath(
			$this->container->get('filesystem.root'),
			'',
			'',
			false,
			[
				'mainfile.php',
				'header.php',
				'footer.php',
				'phoenix.php',
				'Vagrantfile'
			],
			$ignoredExts
		);
		foreach ($paths as $i => $path) {
			$paths[$i] = mb_substr($path, 1);
		}

		/**
		 * @var Filesystem $librariesFileSystem
		 */
		$librariesFileSystem = $this->container->get('filesystem.libraries');

		$extraPaths = [];
		foreach (['image-editor', 'paginationstyles'] as $library) {
			$extraPaths[] = $this->findAccessibleItemsFromPath(
				$librariesFileSystem,
				'libraries',
				$library,
				true,
				[],
				$ignoredExts
			);
		}

		/**
		 * @var Filesystem $modulesFileSystem
		 */
		$modulesFileSystem = $this->container->get('filesystem.modules');

		foreach (ModuleHandler::getActive() as $moduleName) {
			$extraPaths[] = $this->findAccessibleItemsFromPath(
				$modulesFileSystem,
				'modules',
				$moduleName,
				true,
				['admin_header.php', 'icms_version.php'],
				$ignoredExts,
				static function (array $pathParts) {
					return !in_array($pathParts[1], ['language', 'class', 'blocks', 'Extensions'], true);
				}
			);
		}

		$paths = array_merge($paths, ...$extraPaths);
		sort($paths);

		return implode(
			'|',
			array_map(function ($path) {
				return preg_quote($path, '/');
			}, $paths)
		);
	}

	/**
	 * Get old style routes collection
	 *
	 * @return RouteInterface[]
	 *
	 * @throws FilesystemException
	 */
	protected function getOldStyleRoutes(): array
	{
		/**
		 * @var CacheItemPoolInterface $cache
		 */
		$cache = $this->container->get('cache');

		$cachedItem = $cache->getItem('router_legacy_regexp');
		if (!$cachedItem->isHit()) {
			$cachedItem->set(
				$this->getLegacyRegexp()
			);
			$cache->save($cachedItem);
		}

		$collector = new RouteCollector();

		$regexp = $cachedItem->get();
		$collector
			->get('legacy_proxy', "/{path</$regexp/>}", [LegacyController::class, 'proxy'])
			->setMethods('GET', 'POST');

		return $collector->getCollection()->all();
	}
}