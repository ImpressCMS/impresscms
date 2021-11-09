<?php

namespace ImpressCMS\Core\Response;

use Aura\Session\SessionFactory;
use Exception;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use icms;
use ImpressCMS\Core\Controllers\DefaultController;
use ImpressCMS\Core\Facades\Config;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Factory to make default system responses
 *
 * @package ImpressCMS\Core\Response
 */
class ResponseFactory implements ResponseFactoryInterface
{
	/**
	 * @var ContainerInterface
	 */
	private $container;

	/**
	 * ResponseFactory constructor.
	 *
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	/**
	 * @inheritDoc
	 */
	public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
	{
		if ($code >= 400 && $code <= 599) {
			return $this->createErrorPageResponse($code, $reasonPhrase);
		}

		return new Response($code, [], null, '1.1', $reasonPhrase);
	}

	/**
	 * Creates error page response
	 *
	 * @param int $code HTTP response code
	 * @param string $message Message for response
	 *
	 * @return ResponseInterface
	 */
	protected function createErrorPageResponse(int $code, string $message): ResponseInterface
	{
		try {
			/**
			 * @var Config $configHandler
			 */
			$configHandler = icms::handler('icms_config');
			$mainConfig = $configHandler->getConfigsByCat(Config::CATEGORY_MAIN);
		} catch (Exception $exception) {
			$mainConfig = [];
		}

		/**
		 * @var RequestInterface $request
		 */
		$request = $this->container->get('request');

		$sessionName = ($mainConfig['use_mysession'] && $mainConfig['session_name']) ? $mainConfig['session_name'] : 'ICMSSESSION';
		icms::$session = (new SessionFactory())->newInstance(
			$request->getCookieParams()
		);

		$defController = new DefaultController();
		$_GET['e'] = $code;
		$_GET['msg'] = $message;

		$serverRequest = new ServerRequest(
			$request->getMethod(),
			$request->getUri(),
			$request->getHeaders(),
			$request->getBody(),
			$request->getProtocolVersion()
		);

		return $defController->getError(
			$serverRequest
				->withQueryParams($_GET)
				->withParsedBody($_POST)
				->withAttribute('session', icms::$session)
		);
	}
}