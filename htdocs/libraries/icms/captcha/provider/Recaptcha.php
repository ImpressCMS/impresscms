<?php
/**
 * Google reCAPTCHA, v2 (checkbox) and v3 (invisible, score based)
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Captcha
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

class icms_captcha_provider_Recaptcha extends icms_captcha_provider_Remote {

	private const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';
	private const SCRIPT_URL = 'https://www.google.com/recaptcha/api.js';
	private const RESPONSE_FIELD = 'g-recaptcha-response';
	private const V3_ACTION = 'submit';
	private const V3_REFRESH_MILLISECONDS = 90000;

	public function getName(): string {
		return 'recaptcha';
	}

	public function isAvailable(): bool {
		return $this->siteKey() !== '' && $this->secret() !== '';
	}

	public function render(string $name): string {
		$siteKey = htmlspecialchars($this->siteKey(), ENT_QUOTES);
		if (!$this->isV3()) {
			return "<div class='g-recaptcha' data-sitekey='{$siteKey}'></div>"
				. $this->script(self::SCRIPT_URL);
		}

		$id = 'recaptcha_' . bin2hex(random_bytes(4));
		$field = self::RESPONSE_FIELD;
		$action = self::V3_ACTION;
		$refresh = self::V3_REFRESH_MILLISECONDS;

		return "<input type='hidden' name='{$field}' id='{$id}' value='' />"
			. $this->script(self::SCRIPT_URL . '?render=' . rawurlencode($this->siteKey()))
			. "<script>(function () {"
			. "var field = document.getElementById('{$id}');"
			. "function refresh() {"
			. "if (typeof grecaptcha === 'undefined') { return setTimeout(refresh, 250); }"
			. "grecaptcha.ready(function () {"
			. "grecaptcha.execute('{$siteKey}', {action: '{$action}'}).then(function (token) { field.value = token; });"
			. "});"
			. "}"
			. "refresh();"
			. "setInterval(refresh, {$refresh});"
			. "})();</script>";
	}

	public function verify(array $request, string $name): icms_captcha_Result {
		$token = trim((string) ($request[self::RESPONSE_FIELD] ?? ''));
		if ($token === '') {
			return icms_captcha_Result::invalid(ICMS_CAPTCHA_INVALID_CODE);
		}

		$answer = $this->post(self::VERIFY_URL, array(
			'secret' => $this->secret(),
			'response' => $token,
			'remoteip' => $this->remoteAddress(),
		));
		if ($answer === null) {
			return icms_captcha_Result::invalid(ICMS_CAPTCHA_SERVICE_UNREACHABLE);
		}

		if (empty($answer['success'])) {
			return icms_captcha_Result::invalid(ICMS_CAPTCHA_INVALID_CODE);
		}

		if (!$this->isV3()) {
			return icms_captcha_Result::valid();
		}

		$threshold = (float) icms_captcha_Manager::config('captcha_recaptcha_threshold', 0.5);
		$score = (float) ($answer['score'] ?? 0);
		$action = (string) ($answer['action'] ?? '');
		if ($score < $threshold || $action !== self::V3_ACTION) {
			return icms_captcha_Result::invalid(ICMS_CAPTCHA_INVALID_CODE);
		}

		return icms_captcha_Result::valid();
	}

	private function isV3(): bool {
		return icms_captcha_Manager::config('captcha_recaptcha_version', 'v2') === 'v3';
	}

	private function siteKey(): string {
		return trim((string) icms_captcha_Manager::config('captcha_recaptcha_sitekey', ''));
	}

	private function secret(): string {
		return trim((string) icms_captcha_Manager::config('captcha_recaptcha_secret', ''));
	}
}
