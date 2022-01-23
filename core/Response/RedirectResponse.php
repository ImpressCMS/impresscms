<?php


namespace ImpressCMS\Core\Response;

use Aura\Session\Session;
use GuzzleHttp\Psr7\MessageTrait;
use icms;
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
	 * @param bool $allowExternalLink Allow redirect to external link?
	 */
	public function __construct(string $newUrl, int $statusCode = 301, string $redirectMessage = '', bool $allowExternalLink = true)
	{
		if ($redirectMessage) {
			$this->saveRedirectMessageToSession($redirectMessage);
		}

		$this
			->withStatus($statusCode, $redirectMessage)
			->setHeaders([
				'Location' => $this->autoCorrectUrl($newUrl, $allowExternalLink)
			]);
	}

	/**
	 * Saves redirect message to session. This needs to show such message for user.
	 *
	 * @param string $message
	 */
	protected function saveRedirectMessageToSession(string $message): void
	{
		/**
		 * @var Session $session
		 */
		$session = icms::$session;

		$session
			->getSegment(icms::class)
			->setFlash('redirect_message', $message);

		$session->commit();
	}

	/**
	 * Auto corrects redirect URL
	 *
	 * @param string $newUrl URL where to redirect
	 * @param bool $allowExternalLink Do we allow external link redirection?
	 *
	 * @return string
	 */
	protected function autoCorrectUrl(string $newUrl, bool $allowExternalLink): string
	{
		$parsed = parse_url($newUrl);

		if (isset($parsed['scheme']) && in_array(strtolower($parsed['scheme']), ['script', 'javascript', 'about'], true)) {
			return ICMS_URL;
		}

		$isExternal = isset($parsed['host']) && (
				$parsed['host'] !== parse_url(ICMS_URL, PHP_URL_HOST)
			);

		if (!$allowExternalLink && $isExternal) {
			return ICMS_URL;
		}

		if (!$isExternal && $this->needToAppendSID()) {
			if (isset($parsed['query'])) {
				$newUrl .= '?'.SID;
			} else {
				$newUrl .= '&'.SID;
			}
		} elseif (empty($newUrl)) {
			$newUrl = './';
		}

		return $newUrl;
	}

	/**
	 * Need to append SID
	 *
	 * @return bool
	 */
	protected function needToAppendSID(): bool
	{
		if (!defined('SID') || !SID) {
			return false;
		}

		global $icmsConfig;

		$sessionName = session_name();
		if ($icmsConfig['use_mysession'] && $icmsConfig['session_name']) {
			$sessionName = $icmsConfig['session_name'];
		}

		return !isset($_COOKIE[$sessionName]);
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