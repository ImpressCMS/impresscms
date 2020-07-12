<?php
namespace ImpressCMS\Core\View\Form\Elements\Captcha;

use Aura\Session\Session;
use icms;

/**
 * text version of Captcha element
 * Xoops Frameworks addon
 *
 * based on Frameworks::captcha by Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license 	http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author	Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since	XOOPS
 * @author	modified by Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @package	ICMS\Form\Elements\Captcha
 */
class TextMode {

	public $config = array();
	public $code;

	/**
	 * Constructor
	 */
	public function __construct() {
	}

	/**
	 * Creates TextMode object
	 *
	 * @return TextMode
	 */
	public function &instance() {
		static $instance;
		if (!isset($instance)) {
			$instance = new self();
		}
		return $instance;
	}

	/**
	 * Loading configs from CAPTCHA class
	 * @param string	$config	the config array
	 */
	public function loadConfig($config = array()) {
		// Loading default preferences
		$this->config = & $config;
	}

	/**
	 * Render the form
	 * @return string	$form the Captcha Form
	 */
	public function render() {
		global $icmsConfigCaptcha;
		$form = $this->loadText()
			. "&nbsp;&nbsp; <input type='text' name='" . $this->config['name']
			."' id='" . $this->config['name']
			. "' size='" . $icmsConfigCaptcha['captcha_num_chars']
			. "' maxlength='" . $icmsConfigCaptcha['captcha_num_chars']
			. "' value='' />";
		$rule = constant('ICMS_CAPTCHA_RULE_TEXT');
		if (!empty($rule)) {
			$form .= "&nbsp;&nbsp;<small>{$rule}</small>";
		}

		$this->setCode();

		return $form;
	}

	/**
	 * Load the ICMS Captcha Text
	 * @return string	The Captcha Expression
	 */
	public function loadText() {
		$val_a = rand(0, 9);
		$val_b = rand(0, 9);
		if ($val_a > $val_b) {
			$expression = "{$val_a} - {$val_b} = ?";
			$this->code = $val_a - $val_b;
		} else {
			$expression = "{$val_a} + {$val_b} = ?";
			$this->code = $val_a + $val_b;
		}

		return "<span style='font-style: normal; font-weight: bold; font-size: 100%; font-color: #333; border: 1px solid #333; padding: 1px 5px;'>{$expression}</span>";
	}

	/**
	 * Sets CAPTCHA code
	 */
	public function setCode()
	{
		/**
		 * @var Session $session
		 */
		$session = icms::getInstance()->get('session');
		$session->getSegment(Image::class)->set('session_code', (string)$this->code);
	}

}

