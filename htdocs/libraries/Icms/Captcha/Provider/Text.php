<?php
/**
 * Simple arithmetic captcha, needs no external service or extension
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Captcha
 */

namespace Icms\Captcha\Provider;

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

class Text implements \Icms\Captcha\Provider {

	public function getName(): string {
		return 'text';
	}

	public function isAvailable(): bool {
		return true;
	}

	public function render(string $name): string {
		$first = random_int(0, 9);
		$second = random_int(0, 9);
		if ($first > $second) {
			$expression = "{$first} - {$second} = ?";
			$answer = $first - $second;
		} else {
			$expression = "{$first} + {$second} = ?";
			$answer = $first + $second;
		}

		$session =& \Icms\Captcha\Manager::session();
		$session['text'] = (string) $answer;

		$name = htmlspecialchars($name, ENT_QUOTES);
		$html = "<span style='font-style: normal; font-weight: bold; font-size: 100%; border: 1px solid #333; padding: 1px 5px;'>{$expression}</span>"
			. "&nbsp;&nbsp; <input type='text' name='{$name}' id='{$name}' size='4' maxlength='4' value='' autocomplete='off' />";

		$rule = ICMS_CAPTCHA_RULE_TEXT;
		if (!empty($rule)) {
			$html .= "&nbsp;&nbsp;<small>{$rule}</small>";
		}

		return $html;
	}

	public function verify(array $request, string $name): \Icms\Captcha\Result {
		$session =& \Icms\Captcha\Manager::session();
		$expected = $session['text'] ?? '';
		unset($session['text']);

		$given = trim((string) ($request[$name] ?? ''));
		if ($expected === '' || $given === '' || $given !== $expected) {
			return \Icms\Captcha\Result::invalid(ICMS_CAPTCHA_INVALID_CODE);
		}

		return \Icms\Captcha\Result::valid();
	}
}
