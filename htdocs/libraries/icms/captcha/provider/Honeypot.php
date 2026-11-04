<?php
/**
 * Invisible bot trap: a hidden field that humans leave empty and a minimum
 * time between showing and submitting the form
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Captcha
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

class icms_captcha_provider_Honeypot implements icms_captcha_Provider {

	public function getName(): string {
		return 'honeypot';
	}

	public function isAvailable(): bool {
		return true;
	}

	public function render(string $name): string {
		$field = 'hp_' . bin2hex(random_bytes(4));

		$session =& icms_captcha_Manager::session();
		$session['honeypot'] = array(
			'field' => $field,
			'time' => time(),
		);

		return "<div style='position: absolute; left: -10000px; top: auto; width: 1px; height: 1px; overflow: hidden;' aria-hidden='true'>"
			. "<input type='text' name='{$field}' value='' tabindex='-1' autocomplete='off' />"
			. "</div>";
	}

	public function verify(array $request, string $name): icms_captcha_Result {
		$session =& icms_captcha_Manager::session();
		$trap = $session['honeypot'] ?? null;
		unset($session['honeypot']);

		if (!is_array($trap) || !isset($request[$trap['field']])) {
			return icms_captcha_Result::invalid(ICMS_CAPTCHA_INVALID_CODE);
		}

		if ($request[$trap['field']] !== '') {
			return icms_captcha_Result::invalid(ICMS_CAPTCHA_INVALID_CODE);
		}

		$minimumTime = (int) icms_captcha_Manager::config('captcha_honeypot_mintime', 3);
		if (time() - $trap['time'] < $minimumTime) {
			return icms_captcha_Result::invalid(ICMS_CAPTCHA_TOOFAST);
		}

		return icms_captcha_Result::valid();
	}
}
