<?php
/**
 * Text form for CAPTCHA
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		FormCaptcha
 * @since		XOOPS
 * @author		http://www.xoops.org/ The XOOPS Project
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @author	   Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		$Id: text.php 19090 2010-03-13 17:41:42Z skenow $
 */

class icms_view_captcha_Text {
	var $config	= array();
	var $code;

	/**
	 * Constructor
	 */
	function icms_view_captcha_Text()
	{
	}

	/**
	 * Creates icms_view_captcha_Text object
	 * @return object	reference to icms_view_captcha_Text (@link icms_view_captcha_Text) Object
	 */
	function &instance()
	{
		static $instance;
		if (!isset($instance)) {
			$instance = new icms_view_captcha_Text();
		}
		return $instance;
	}

	/**
	 * Loading configs from CAPTCHA class
	 * @param string	$config	the config array
	 */
	function loadConfig($config = array())
	{
		// Loading default preferences
		$this->config =& $config;
	}

	/**
	 * Sets CAPTCHA code
	 */
	function setCode()
	{
		$_SESSION['icms_view_captcha_Object_sessioncode'] = strval( $this->code );
	}

	/**
	 * Render the form
	 * @return string	$form the Captcha Form
	 */
	function render()
	{
		global $icmsConfigCaptcha;
		$form = $this->loadText()  . "&nbsp;&nbsp; <input type='text' name='".$this->config["name"]."' id='".$this->config["name"]."' size='" . $icmsConfigCaptcha['captcha_num_chars'] . "' maxlength='" . $icmsConfigCaptcha['captcha_num_chars'] . "' value='' />";
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
	function loadText()
	{
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

?>