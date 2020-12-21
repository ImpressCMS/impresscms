<?php


namespace ImpressCMS\Core\Middlewares;


use Aura\Session\Session;
use icms;
use icms_member_Handler;
use icms_member_user_Object;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Creates current user attribute in request
 *
 * @package ImpressCMS\Core\Middlewares
 */
class UserMiddleware implements MiddlewareInterface
{

	/**
	 * @inheritDoc
	 */
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		/**
		 * @var Session $session
		 */
		$session = $request->getAttribute('session');

		// Todo: remove this once possible
		icms::$session = $session;

		$userSection = $session->getSegment('user');

		if ($userId = $userSection->get('userid')) {
			/**
			 * @var icms_member_Handler $userHandler
			 */
			$userHandler = icms::handler('icms_member');
			$user = $userHandler->getUser($userId);
			if (!is_object($user)) {
				$session->regenerateId();
				$session->clear();
			} else {
				$user->setGroups(
					$userSection->get('groups')
				);
				if (!$userSection->get('language')) {
					$userSection->set('language', $user->language);
				}
			}
		} else {
			$user = null;
		}

		// Todo: remove this once possible
		icms::$user = $user;

		return $handler->handle(
			$request->withAttribute('user', $user)
		);
	}
}