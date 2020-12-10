<?php

namespace ImpressCMS\Core\Middlewares;

use icms;
use icms_module_Object;
use ImpressCMS\Core\Controllers\LegacyController;
use League\Route\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Middleware that detects module
 *
 * @package ImpressCMS\Core\Middlewares
 */
class DetectModuleMiddleware implements MiddlewareInterface
{

	/**
	 * @inheritDoc
	 */
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		$stack = $handler->getMiddlewareStack();
		$moduleName = null;
		if (!empty($stack)) {
			$route = end($stack);
			if ($route instanceof Route) {
				list($controller, $method) = $route->getCallable();
				$class = get_class($controller);
				if ($class === LegacyController::class) {
					if (preg_match('/modules\/([^\/]+)/', $request->getRequestTarget(), $matches)) {
						$moduleName = $matches[1];
					}
				} elseif (preg_match($class, '/^ImpressCMS\\Modules\\([^\\]+)\\/', $matches) === 0) {
					$moduleName = $matches[1];
				}
			}
		}

		$module = null;
		if ($moduleName !== null) {
			/**
			 * @var icms_module_Object $module
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