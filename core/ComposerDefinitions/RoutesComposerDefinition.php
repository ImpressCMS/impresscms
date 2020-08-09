<?php


namespace ImpressCMS\Core\ComposerDefinitions;

use Defuse\Crypto\Key;
use Ellipse\Cookies\EncryptCookiesMiddleware;
use icms;
use icms_config_Handler;
use ImpressCMS\Core\Controllers\LegacyController;
use ImpressCMS\Core\Exceptions\RoutePathUndefinedException;
use League\Container\Container;
use League\Route\Strategy\ApplicationStrategy;
use League\Route\Strategy\JsonStrategy;

/**
 * let register routes in composer.json
 *
 * @package ImpressCMS\Core\ComposerDefinitions
 */
class RoutesComposerDefinition implements ComposerDefinitionInterface
{

	/**
	 * @inheritDoc
	 */
	public function load(Container $container)
	{
		$router = $container->get('router');
		require $this->getCacheFilename();
	}

	/**
	 * Gets filename for cached data
	 *
	 * @return string
	 */
	public function getCacheFilename(): string
	{
		return ICMS_CACHE_PATH . '/routes.php';
	}

	/**
	 * @inheritDoc
	 */
	public function needsUpdate(string $composerPath): bool
	{
		$filename = $this->getCacheFilename();

		return (!file_exists($filename)) ||
			(filemtime($filename) < filemtime($composerPath . '/composer.json')) ||
			(filemtime($filename) < filemtime($composerPath . '/composer.lock'));
	}

	/**
	 * Adds middlewares depending on config
	 *
	 * @param string[] $ret Cache file config
	 */
	protected function addMiddlewaresDependingOnConfig(array &$ret): void {
		/**
		 * @var icms_config_Handler $configHandler
		 */
		$configHandler = icms::handler('icms_config');
		$mainConfig = $configHandler->getConfigsByCat(icms_config_Handler::CATEGORY_MAIN);

		if ($mainConfig['encrypt_cookies']) {
			$ret[] = '$router->middleware(';
			$ret[] = '    new \\' . EncryptCookiesMiddleware::class.'(';
			$ret[] = '        \\' . Key::class . '::loadFromAsciiSafeString(';
			$ret[] = '             env(\'APP_KEY\')';
			$ret[] = '        )';
			$ret[] = '    )';
			$ret[] = ');';
		}

		if ($mainConfig['gzip_compression']) {
			$ret[] = '$router->lazyMiddleware(\'\\Middlewares\\GzipEncoder\');';
			$ret[] = '$router->lazyMiddleware(\'\\Middlewares\\DeflateEncoder\');';
		}
	}

	/**
	 * @inheritDoc
	 */
	public function updateCache(array $data): void
	{
		$ret = [
			'<?php'
		];

		$this->addMiddlewaresDependingOnConfig($ret);

		$routes = array_merge(
			$this->getOldStyleRoutes(),
			$data['routes'] ?? []
		);
		$prefixOfRoute = dirname($_SERVER['SCRIPT_NAME']);
		if (substr($prefixOfRoute, -1) === '/') {
			$prefixOfRoute = substr($prefixOfRoute, 0, -1);
		}
		foreach ($routes as $definition) {
			foreach ($this->generateDefinitionVariants($definition) as $parsedDefinition) {
				$hasPort = isset($parsedDefinition['port']);
				$hasHost = isset($parsedDefinition['host']);
				$hasScheme = isset($parsedDefinition['scheme']);
				$hasStrategy = ($parsedDefinition['strategy'] !== ApplicationStrategy::class);
				$hasMiddlewares = isset($parsedDefinition['middlewares']);
				$hasExtraConfig = $hasHost || $hasPort || $hasScheme || $hasStrategy || $hasMiddlewares;
				$ret[] = '$router';
				$ret[] = sprintf(
					'    ->map(%s, %s, %s)%s',
					var_export($parsedDefinition['method'], true),
					var_export($prefixOfRoute . $parsedDefinition['path'], true),
					var_export($parsedDefinition['handler'], true),
					$hasExtraConfig ? '' : ';'
				);
				if ($hasStrategy) {
					$hasExtraConfig = $hasHost || $hasPort || $hasScheme || $hasMiddlewares;
					$ret[] = '    ->setStrategy(';
					$ret[] = sprintf('        $container->get(%s)', var_export($parsedDefinition['strategy'], true));
					$ret[] = '    )' . ($hasExtraConfig ? '' : ';');
				}
				if ($hasMiddlewares) {
					$hasExtraConfig = $hasHost || $hasPort || $hasScheme;
					$mCount = count($parsedDefinition['middlewares']);
					for ($i = 0; $i < $mCount; $i++) {
						$middleware = $parsedDefinition['middlewares'][$i];
						$isLast = $i === ($mCount - 1);
						$ret[] = '    ->middleware(';
						$ret[] = sprintf('        $container->get(%s)', var_export($middleware, true));
						$ret[] = '    )' . (($hasExtraConfig || !$isLast) ? '' : ';');
					}
				}
				if ($hasScheme) {
					$hasExtraConfig = $hasPort || $hasHost;
					$ret[] = sprintf(
						'    ->setScheme(%s)%s',
						var_export($parsedDefinition['scheme'], true),
						$hasExtraConfig ? '' : ';'
					);
				}
				if ($hasHost) {
					$ret[] = sprintf(
						'    ->setHost(%s)%s',
						var_export($parsedDefinition['host'], true),
						$hasPort ? '' : ';'
					);
				}
				if ($hasPort) {
					$ret[] = sprintf(
						'    ->setPort(%s);',
						var_export($parsedDefinition['port'], true)
					);
				}
			}
		}

		file_put_contents($this->getCacheFilename(), implode(PHP_EOL, $ret), LOCK_EX);
	}

	/**
	 * Get old style routes collection
	 *
	 * @return array
	 */
	protected function getOldStyleRoutes(): array
	{
		$paths = [
			ICMS_LIBRARIES_PATH . '/image-editor',
			ICMS_LIBRARIES_PATH . '/paginationstyles',
		];
		foreach (\icms_module_Handler::getActive() as $moduleName) {
			$paths[] = ICMS_MODULES_PATH . '/' . $moduleName;
		}
		$ret = [];
		/**
		 * @var \SplFileInfo $fileInfo
		 */
		foreach (new \FilesystemIterator(ICMS_ROOT_PATH, \FilesystemIterator::CURRENT_AS_FILEINFO |
			\FilesystemIterator::SKIP_DOTS) as $fileInfo) {
			if ($fileInfo->isDir()) {
				continue;
			}
			if ($fileInfo->getExtension() !== 'php') {
				continue;
			}
			if (strpos($fileInfo->getFilename(), '.') === 0) {
				continue;
			}
			if (in_array($fileInfo->getFilename(), ['mainfile.php', 'header.php', 'footer.php', 'phoenix.php'])) {
				continue;
			}
			$ret[] = [
				'path' => '/' . $fileInfo->getFilename(),
				'method' => ['GET', 'POST'],
				'handler' => LegacyController::class . '::proxy',
			];
		}
		foreach ($paths as $path) {
			foreach ($this->getOldStyleRoutesForPath($path) as $route) {
				$ret[] = $route;
			}
		}
		return $ret;
	}

	/**
	 * Get old style routes for file path
	 *
	 * @param string $path Filepath for witch get routes
	 *
	 * @return array
	 */
	protected function getOldStyleRoutesForPath(string $path): array
	{
		$ret = [];
		$directoryIterator = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator(
				$path,
				\FilesystemIterator::CURRENT_AS_FILEINFO |
				\FilesystemIterator::SKIP_DOTS
			)
		);

		$handler = LegacyController::class . '::proxy';
		/**
		 * @var \SplFileInfo $fileInfo
		 */
		foreach ($directoryIterator as $fileInfo) {
			$path = str_replace(ICMS_ROOT_PATH, '', $fileInfo->getPath()) . '/' . $fileInfo->getFilename();
			if ($fileInfo->isDir()) {
				continue;
			}
			if (in_array(strtolower($fileInfo->getExtension()), ['php', 'html', 'htm', 'txt'])) {
				foreach (['language', 'migrations', 'templates', 'class', 'blocks'] as $badPath) {
					if (strpos($path, '/' . $badPath . '/') !== false) {
						continue 2;
					}
				}
			}
			if ($fileInfo->getFilename() === 'icms_version.php') {
				continue;
			}
			$method = ($fileInfo->getExtension() === 'php') ? ['GET', 'POST'] : 'GET';
			$ret[] = compact('method', 'handler', 'path');
		}
		return $ret;
	}

	/**
	 * Generate route definition variants
	 *
	 * @param array $definition Route definition
	 *
	 * @return array
	 *
	 * @throws RoutePathUndefinedException
	 */
	protected function generateDefinitionVariants(array $definition): array
	{
		$ret = [];
		$methods = (array)($definition['method'] ?? 'GET');
		$handler = $definition['handler'];
		if (!isset($definition['path'])) {
			throw new RoutePathUndefinedException();
		}
		$path = $definition['path'];
		if (isset($definition['strategy'])) {
			switch (strtolower(trim($definition['strategy']))) {
				case 'json':
					$strategy = JsonStrategy::class;
					break;
				case 'default':
				case 'app':
				case 'application':
					$strategy = ApplicationStrategy::class;
					break;
				default:
					$strategy = $definition['strategy'];
			}
		} else {
			$strategy = ApplicationStrategy::class;
		}
		foreach ($methods as $method) {
			$ret[] = compact('method', 'path', 'handler', 'strategy');
		}
		foreach (['port', 'host', 'scheme'] as $option) {
			if (isset($definition[$option]) && !empty($definition[$option])) {
				$values = (array)$definition[$option];
				foreach ($ret as $i => $v) {
					foreach ($values as $value) {
						$ret[$i][$option] = $value;
					}
				}
			}
		}
		if (isset($definition['middlewares']) && !empty($definition['middlewares'])) {
			foreach ($ret as $i => $v) {
				$ret[$i]['middlewares'] = (array)$v;
			}
		}

		return $ret;
	}

}