<?php
/**
 * CAPTCHA form element
 *
 * The challenge itself is delegated to the provider chosen in the captcha_mode
 * preference, see icms_captcha_Manager.
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 * @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/*
 * Usage
 *
 * For form creation:
 * $form->addElement(new icms_form_elements_Captcha($caption, $name));
 *
 * For verification:
 * $result = icms_captcha_Manager::instance()->verify();
 * if (!$result->isValid()) {
 *   echo $result->getMessage();
 * }
 */
class icms_form_elements_Captcha extends icms_form_Element {

	/**
	 * @param string $caption Caption of the form element, default value is defined in language/captcha.php
	 * @param string $name Name of the input box
	 *
	 * The remaining arguments are accepted for backwards compatibility only,
	 * the preferences of the captcha category decide how the challenge looks.
	 */
	public function __construct($caption = '', $name = 'icmscaptcha', $skipmember = null,
			$numchar = null, $minfontsize = null, $maxfontsize = null, $backgroundtype = null,
			$backgroundnum = null
	) {
		$this->setName($name);

		if (!icms_captcha_Manager::instance()->isActive()) {
			$this->setHidden();
			return;
		}

		$this->setCaption(!empty($caption) ? $caption : ICMS_CAPTCHA_CAPTION);
	}

	public function render() {
		if ($this->isHidden()) {
			return '';
		}

		return icms_captcha_Manager::instance()->render($this->getName(false));
	}
}
