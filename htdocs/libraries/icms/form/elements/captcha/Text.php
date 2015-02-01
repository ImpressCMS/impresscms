<?php
/**
 * text version of Captcha element
 * Xoops Frameworks addon
 *
 * based on Frameworks::captcha by Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license 	http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since		XOOPS
 *
 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @version		SVN: $Id: Text.php 12340 2013-09-22 04:11:09Z skenow $
 */

/**
 * Creates text version of Captcha element
 *
 * @author		modified by Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 */
class icms_form_elements_captcha_Text {

	public $config = array();
	public $code;

	/**
	 * Constructor
	 */
	public function __construct() {
	}

	/**
	 * Creates icms_form_elements_captcha_Text object
	 * @return object	reference to icms_form_elements_captcha_Text (@link icms_form_elements_captcha_Text) Object
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
		$this->config =& $config;
	}

	/**
	 * Sets CAPTCHA code
	 */
	public function setCode() {
		$_SESSION['icms_form_elements_captcha_Object_sessioncode'] = strval($this->code);
	}

	/**
	 * Render the form
	 * @return string	$form the Captcha Form
	 */
	public function render() {
		global $icmsConfigCaptcha;
		$form = $this->loadText()
			. "&nbsp;&nbsp; <input type='text' name='" . $this->config["name"]
			."' id='" . $this->config["name"]
			. "' size='" . $icmsConfigCaptcha['captcha_num_chars']
			. "' maxlength='" . $icmsConfigCaptcha['captcha_num_chars']
			. "' value='' />";
		$rule = constant("ICMS_CAPTCHA_RULE_TEXT");
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

}
