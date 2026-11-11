<?php
/**
 * Cloudflare Turnstile
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Captcha
 */

namespace Icms\Captcha\Provider;

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

class Turnstile extends Remote {

	private const VERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
	private const SCRIPT_URL = 'https://challenges.cloudflare.com/turnstile/v0/api.js';
	private const RESPONSE_FIELD = 'cf-turnstile-response';

	public function getName(): string {
		return 'turnstile';
	}

	public function isAvailable(): bool {
		return $this->siteKey() !== '' && $this->secret() !== '';
	}

	public function render(string $name): string {
		$siteKey = htmlspecialchars($this->siteKey(), ENT_QUOTES);

		return "<div class='cf-turnstile' data-sitekey='{$siteKey}'></div>"
			. $this->script(self::SCRIPT_URL);
	}

	public function verify(array $request, string $name): \Icms\Captcha\Result {
		$token = trim((string) ($request[self::RESPONSE_FIELD] ?? ''));
		if ($token === '') {
			return \Icms\Captcha\Result::invalid(ICMS_CAPTCHA_INVALID_CODE);
		}

		$answer = $this->post(self::VERIFY_URL, array(
			'secret' => $this->secret(),
			'response' => $token,
			'remoteip' => $this->remoteAddress(),
		));
		if ($answer === null) {
			return \Icms\Captcha\Result::invalid(ICMS_CAPTCHA_SERVICE_UNREACHABLE);
		}

		return empty($answer['success'])
			? \Icms\Captcha\Result::invalid(ICMS_CAPTCHA_INVALID_CODE)
			: \Icms\Captcha\Result::valid();
	}

	private function siteKey(): string {
		return trim((string) \Icms\Captcha\Manager::config('captcha_turnstile_sitekey', ''));
	}

	private function secret(): string {
		return trim((string) \Icms\Captcha\Manager::config('captcha_turnstile_secret', ''));
	}
}
