<?php
/**
 * Image Creation class for CAPTCHA
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
 * @version		SVN: $Id: Image.php 12340 2013-09-22 04:11:09Z skenow $
 */

/**
 * The captcha image
 *
 * @author		modified by Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 *
 */
class icms_form_elements_captcha_Image {
	//var $config	= array();

	/**
	 * Constructor
	 */
	public function __construct() {
	}

	/**
	 * Creates instance of icmsCaptchaImage
	 * @return  object the icms_form_elements_captcha_Image object
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
	 * @param   array $config the configuration array
	 */
	public function loadConfig($config = array()) {
		// Loading default preferences
		$this->config =& $config;
	}

	/**
	 * Renders the Captcha image Returns form with image in it
	 * @return  string String that contains the Captcha Image form
	 */
	public function render() {
		global $icmsConfigCaptcha;
		$form = "<input type='text' name='" . $this->config["name"]
			. "' id='" . $this->config["name"]
			. "' size='" . $icmsConfigCaptcha['captcha_num_chars']
			. "' maxlength='" . $icmsConfigCaptcha['captcha_num_chars']
			. "' value='' /> &nbsp; ". $this->loadImage();
		$rule = htmlspecialchars(ICMS_CAPTCHA_REFRESH, ENT_QUOTES);
		if ($icmsConfigCaptcha['captcha_maxattempt']) {
			$rule .=  " | ". sprintf(constant("ICMS_CAPTCHA_MAXATTEMPTS"), $icmsConfigCaptcha['captcha_maxattempt']);
		}
		$form .= "&nbsp;&nbsp;<small>{$rule}</small>";

		return $form;
	}

	/**
	 * Loads the Captcha Image
	 * @return  string String that contains the Captcha image
	 */
	public function loadImage() {
		global $icmsConfigCaptcha;
		$rule = $icmsConfigCaptcha['captcha_casesensitive'] ? constant("ICMS_CAPTCHA_RULE_CASESENSITIVE") : constant("ICMS_CAPTCHA_RULE_CASEINSENSITIVE");
		$ret = "<img id='captcha' src='" . ICMS_URL . "/libraries/icms/form/elements/captcha/img.php' onclick=\"this.src='" . ICMS_URL . "/libraries/icms/form/elements/captcha/img.php?refresh='+Math.random()"
					."\" style='cursor: pointer;margin-left: auto;margin-right: auto;text-align:center;' alt='" . htmlspecialchars($rule, ENT_QUOTES) . "' />";
		return $ret;
	}
}
