<?php


namespace ImpressCMS\Core\Response;

use GuzzleHttp\Psr7\MessageTrait;
use ImpressCMS\Core\Exceptions\ResponseCodeUnsupportedException;
use Psr\Http\Message\ResponseInterface;

class RedirectResponse implements ResponseInterface
{
	use MessageTrait;

	/**
	 * Response code
	 *
	 * @var int
	 */
	protected $code;

	/**
	 * Reason phrase for response message
	 *
	 * @var string
	 */
	protected $reasonPhrase;

	/**
	 * RedirectResponse constructor.
	 *
	 * @param string $newUrl Where to redirect
	 * @param int $statusCode With what HTTP status code
	 * @param string $redirectMessage Redirection message
	 */
	public function __construct(string $newUrl, int $statusCode = 301, string $redirectMessage = '')
	{
		$this
			->withStatus($statusCode, $redirectMessage)
			->setHeaders([
				'Location' => $newUrl
			]);
	}

	/**
	 * @inheritDoc
	 */
	public function getStatusCode()
	{
		return $this->code;
	}

	/**
	 * @inheritDoc
	 */
	public function withStatus($code, $reasonPhrase = '')
	{
		if (!in_array($code, [301, 303, 307, 308])) {
			throw new ResponseCodeUnsupportedException();
		}

		$this->code = $code;
		$this->reasonPhrase = $reasonPhrase;

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function getReasonPhrase()
	{
		return $this->reasonPhrase;
	}
}