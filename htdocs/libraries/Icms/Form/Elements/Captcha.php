<?php
/**
 * CAPTCHA form element
 *
 * The challenge itself is delegated to the provider chosen in the captcha_mode
 * preference, see \Icms\Captcha\Manager.
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 * @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 */
namespace Icms\Form\Elements;
defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/*
 * Usage
 *
 * For form creation:
 * $form->addElement(new \Icms\Form\Elements\Captcha($caption, $name));
 *
 * For verification:
 * $result = \Icms\Captcha\Manager::instance()->verify();
 * if (!$result->isValid()) {
 *   echo $result->getMessage();
 * }
 */
/**
 * CAPTCHA form element
 *
 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 *
 */
class Captcha extends \Icms\Form\Element {

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

		if (!\Icms\Captcha\Manager::instance()->isActive()) {
			$this->setHidden();
			return;
		}

		$this->setCaption(!empty($caption) ? $caption : ICMS_CAPTCHA_CAPTION);
	}

	/**
	 * @see \Icms\Form\Element::render()
	 */
	public function render(): string {
		if ($this->isHidden()) {
			return '';
		}

		return \Icms\Captcha\Manager::instance()->render($this->getName(false));
	}
}
