<?php

namespace ImpressCMS\Core\Middlewares;

use icms;
use ImpressCMS\Core\Models\Module;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionClass;
use RuntimeException;
use Sunrise\Http\Router\RequestHandler\CallableRequestHandler;
use Sunrise\Http\Router\Router;
use Throwable;

/**
 * Middleware that detects module
 *
 * @package ImpressCMS\Core\Middlewares
 */
class DetectModuleMiddleware implements MiddlewareInterface
{
	/**
	 * @var Router
	 */
	private $router;

	/**
	 * DetectModuleMiddleware constructor.
	 *
	 * @param Router $router
	 */
	public function __construct(Router $router)
	{
		$this->router = $router;
	}

	/**
	 * @inheritDoc
	 */
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		try {
			$route = $this->router->match($request);
			if ($route->getName() === 'legacy_proxy') {
				if (preg_match('/modules\/([^\/]+)/', $request->getRequestTarget(), $matches)) {
					$moduleName = $matches[1];
				} else {
					$moduleName = null;
				}
			} else {
				$requestHandler = $route->getRequestHandler();
				if ($requestHandler instanceof CallableRequestHandler) {
					$callback = $requestHandler->getCallback();
					if (isset($callback[0])) {
						$reflection = new ReflectionClass($callback[0]);
						$filename = $reflection->getFileName();
						if (str_starts_with($filename, ICMS_MODULES_PATH)) {
							$croppedPath = trim(
								mb_substr($filename, mb_strlen(ICMS_MODULES_PATH)),
								DIRECTORY_SEPARATOR
							);
							$i = mb_strpos($croppedPath, DIRECTORY_SEPARATOR);
							if ($i !== false) {
								$moduleName = mb_substr($croppedPath, 0, $i);
							} else {
								$moduleName = $croppedPath;
							}
						} else {
							$moduleName = null;
						}
					} else {
						$moduleName = null; // we think that non controller callbacks can come only from core
					}
				} else {
					throw new RuntimeException('Unsupported request handler');
				}
			}
		} catch (Throwable $throwable) {
			$moduleName = null;
		}

		$module = null;
		if ($moduleName !== null) {
			/**
			 * @var Module $module
			 */
			$module = icms::handler('icms_module')->getByDirname($moduleName, true);
			$module->launch();
		}

		/**
		 * For legacy compatibility
		 *
		 * @todo Remove these lines once possible
		 */
		global $icmsModule;
		icms::$module = &$module;
		$icmsModule = &$module;

		$request->withAttribute('module', $module);

		return $handler->handle($request);
	}
}