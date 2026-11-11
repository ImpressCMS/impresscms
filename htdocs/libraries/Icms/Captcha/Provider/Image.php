<?php
/**
 * GD generated image captcha, the image itself is served by
 * libraries/Icms/Form/Elements/Captcha/img.php
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Captcha
 */

namespace Icms\Captcha\Provider;

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

class Image implements \Icms\Captcha\Provider {

	private const REQUIRED_FUNCTIONS = array(
		'imagecreatetruecolor', 'imagecolorallocate', 'imagefilledrectangle',
		'imagejpeg', 'imagedestroy', 'imageftbbox', 'imagefttext'
	);

	public function getName(): string {
		return 'image';
	}

	public function isAvailable(): bool {
		if (!extension_loaded('gd')) {
			return false;
		}

		foreach (self::REQUIRED_FUNCTIONS as $function) {
			if (!function_exists($function)) {
				return false;
			}
		}

		return true;
	}

	public function render(string $name): string {
		$session =& \Icms\Captcha\Manager::session();
		unset($session['image']);

		$name = htmlspecialchars($name, ENT_QUOTES);
		$length = (int) \Icms\Captcha\Manager::config('captcha_num_chars', 4);
		$caseSensitive = \Icms\Captcha\Manager::config('captcha_casesensitive', 0);
		$rule = $caseSensitive ? ICMS_CAPTCHA_RULE_CASESENSITIVE : ICMS_CAPTCHA_RULE_CASEINSENSITIVE;
		$source = ICMS_URL . '/libraries/Icms/Form/Elements/Captcha/img.php';

		$html = "<input type='text' name='{$name}' id='{$name}' size='{$length}' maxlength='{$length}' value='' autocomplete='off' /> &nbsp; "
			. "<img id='{$name}_image' src='{$source}' onclick=\"this.src='{$source}?refresh='+Math.random()\" "
			. "style='cursor: pointer; margin-left: auto; margin-right: auto; text-align: center;' alt='" . htmlspecialchars($rule, ENT_QUOTES) . "' />"
			. "&nbsp;&nbsp;<small>" . htmlspecialchars(ICMS_CAPTCHA_REFRESH, ENT_QUOTES) . "</small>";

		return $html;
	}

	public function verify(array $request, string $name): \Icms\Captcha\Result {
		$session =& \Icms\Captcha\Manager::session();
		$expected = $session['image'] ?? '';
		unset($session['image']);

		$given = trim((string) ($request[$name] ?? ''));
		if ($expected === '' || $given === '') {
			return \Icms\Captcha\Result::invalid(ICMS_CAPTCHA_INVALID_CODE);
		}

		$matches = \Icms\Captcha\Manager::config('captcha_casesensitive', 0)
			? strcmp($given, $expected) === 0
			: strcasecmp($given, $expected) === 0;

		return $matches
			? \Icms\Captcha\Result::valid()
			: \Icms\Captcha\Result::invalid(ICMS_CAPTCHA_INVALID_CODE);
	}
}
