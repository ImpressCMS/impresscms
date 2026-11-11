<?php
/**
 * Base for providers that verify a token with an external service
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Captcha
 */

namespace Icms\Captcha\Provider;

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

abstract class Remote implements \Icms\Captcha\Provider {

	private const TIMEOUT_SECONDS = 10;

	/**
	 * POST form data and decode the JSON answer
	 *
	 * @param array<string, string> $fields
	 * @return ?array<string, mixed> null when the service could not be reached
	 */
	protected function post(string $url, array $fields): ?array {
		$body = http_build_query($fields, '', '&');

		$response = function_exists('curl_init')
			? $this->postWithCurl($url, $body)
			: $this->postWithStream($url, $body);
		if ($response === null) {
			return null;
		}

		$decoded = json_decode($response, true);

		return is_array($decoded) ? $decoded : null;
	}

	/**
	 * Only add the loader script once per page, however many forms use it
	 */
	protected function script(string $source): string {
		static $loaded = array();

		if (isset($loaded[$source])) {
			return '';
		}
		$loaded[$source] = true;

		return "<script src='{$source}' async defer></script>";
	}

	protected function remoteAddress(): string {
		return (string) ($_SERVER['REMOTE_ADDR'] ?? '');
	}

	private function postWithCurl(string $url, string $body): ?string {
		$curl = curl_init($url);
		curl_setopt_array($curl, array(
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $body,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => self::TIMEOUT_SECONDS,
			CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
		));
		$response = curl_exec($curl);
		curl_close($curl);

		return is_string($response) ? $response : null;
	}

	private function postWithStream(string $url, string $body): ?string {
		$context = stream_context_create(array('http' => array(
			'method' => 'POST',
			'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
			'content' => $body,
			'timeout' => self::TIMEOUT_SECONDS,
		)));
		$response = @file_get_contents($url, false, $context);

		return is_string($response) ? $response : null;
	}
}
