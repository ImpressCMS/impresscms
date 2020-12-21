<?php


namespace ImpressCMS\Core\Middlewares;

use icms;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Middleware that updates online info if multilogin is enabled
 *
 * @package ImpressCMS\Core\Middlewares
 */
class MultiLoginOnlineInfoUpdaterMiddleware implements MiddlewareInterface
{

	/**
	 * @inheritDoc
	 */
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		/**
		 * @var \icms_member_user_Object|null
		 */
		$user = $request->getAttribute('user');

		if (is_object($user)) {
			/**
			 * @var \icms_core_OnlineHandler $onlineHandler
			 */
			$onlineHandler = icms::handler('icms_core_Online');
			$onlineHandler->write(
				$user->uid,
				$user->uname,
				time(),
				0,
				$request->getAttribute('client-ip')
			);
		}

		return $handler->handle($request);
	}
}