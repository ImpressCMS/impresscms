<?php

namespace ImpressCMS\Core\Middlewares;

use icms;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Checks if user has specific group to access content
 *
 * @package ImpressCMS\Core\Middlewares
 */
class HasGroupMiddleware implements MiddlewareInterface
{
	/**
	 * Group to use for verification
	 *
	 * @var int
	 */
	private $group;

	/**
	 * @var ResponseFactoryInterface
	 */
	private $responseFactory;

	/**
	 * HasGroupMiddleware constructor.
	 *
	 * @param int $group Id that belongs to group that user must have such group
	 * @param ResponseFactoryInterface $responseFactory Response factory that will return response when error happens
	 */
	public function __construct(int $group, ResponseFactoryInterface $responseFactory)
	{
		$this->group = $group;
		$this->responseFactory = $responseFactory;
	}

	/**
	 * @inheritDoc
	 */
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		$groups = icms::$user ? icms::$user->getGroups() : [ICMS_GROUP_ANONYMOUS];
		if (in_array($this->group, $groups, false)) {
			return $handler->handle($request);
		}

		return $this->responseFactory->createResponse(403);
	}
}