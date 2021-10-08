<?php

namespace ImpressCMS\Core\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Sets session cookie config
 *
 * @package ImpressCMS\Core\Middlewares
 */
class SetSessionCookieConfigMiddleware implements MiddlewareInterface
{
	/**
	 * @var int
	 */
	private $sessionExpire;

	/**
	 * @var string
	 */
	private $domain;

	/**
	 * @var bool
	 */
	private $secure;

	/**
	 * SetSessionCookiesMiddleware constructor.
	 *
	 * @param int $sessionExpire
	 * @param string $domain
	 * @param bool $secure
	 */
	public function __construct(int $sessionExpire, string $domain, bool $secure)
	{
		$this->sessionExpire = $sessionExpire;
		$this->domain = $domain;
		$this->secure = $secure;
	}

	/**
	 * @inheritDoc
	 */
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		$request->getAttribute('session')->setCookieParams([
			'secure' => $this->secure,
			'httponly' => true,
			'domain' => $this->domain,
			'lifetime' => $this->sessionExpire
		]);

		return $handler->handle($request);
	}
}