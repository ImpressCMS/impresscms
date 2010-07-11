<?php
/**
 * CAPTCHA class For XOOPS
 *
 * Currently there are two types of CAPTCHA forms, text and image
 * The default mode is "text", it can be changed in the priority:
 * 1 If mode is set through icms_form_elements_Captcha::setConfig("mode", $mode), take it
 * 2 Elseif mode is set though captcha/config.php, take it
 * 3 Else, take "text"
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
 * @version		$Id: captcha.php 19612 2010-06-24 23:28:29Z malanciault $
 */

icms_loadLanguageFile('core', 'captcha');
class icms_captcha_Object {
	var $active	= true;
	var $mode 	= "text";	// potential values: image, text
	var $config	= array();

	var $message = array(); // Logging error messages

	/**
	 * Constructor
	 */
	function icms_captcha_Object()
	{
		// Loading default preferences
		$this->config = @include dirname(__FILE__)."/config.php";

		global $icmsConfigCaptcha;
		$this->setMode($icmsConfigCaptcha['captcha_mode']);
	}

	/**
	 * Creates instance of icms_captcha_Object Object
	 * @return  object Reference to the icms_captcha_Object Object
	 */
	static public function &instance()
	{
		static $instance;
		if (!isset($instance)) {
			$instance = new icms_captcha_Object();
		}
		return $instance;
	}

	/**
	 * Sets the Captcha Config
	 * @param   string $name Config Name
	 * @param   string $val Config Value
	 * @return  bool  Always returns true if the setting of the config has succeeded
	 */
	function setConfig($name, $val)
	{
		if ($name == "mode") {
			$this->setMode($val);
		} elseif (isset($this->$name)) {
			$this->$name = $val;
		} else {
			$this->config[$name] = $val;
		}
		return true;
	}

	/**
	 * Set CAPTCHA mode
	 *
	 * For future possible modes, right now force to use text or image
	 *
	 * @param string	$mode	if no mode is set, just verify current mode
	 */
	function setMode($mode = null)
	{
		if (!empty($mode) && in_array($mode, array("text", "image"))) {
			$this->mode = $mode;

			if ($this->mode != "image") {
				return;
			}
		}

		// Disable image mode
		if (!extension_loaded('gd')) {
			$this->mode = "text";
		} else {
			$required_functions = array("imagecreatetruecolor", "imagecolorallocate", "imagefilledrectangle", "imagejpeg", "imagedestroy", "imageftbbox");
			foreach ($required_functions as $func) {
				if (!function_exists($func)) {
					$this->mode = "text";
					break;
				}
			}
		}

	}

	/**
	 * Initializing the CAPTCHA class
	 * @param   string  $name			 name of the instance
	 * @param   string  $skipmember	   Skip the captcha because the user is member / logged in
	 * @param   string  $num_chars		comes from config, just initializes the variable
	 * @param   string  $fontsize_min	 comes from config, just initializes the variable
	 * @param   string  $fontsize_max	 comes from config, just initializes the variable
	 * @param   string  $background_type  comes from config, just initializes the variable
	 * @param   string  $background_num   comes from config, just initializes the variable
	 */
	function init($name = 'icmscaptcha', $skipmember = null, $num_chars = null, $fontsize_min = null, $fontsize_max = null, $background_type = null, $background_num = null)
	{
		global $icmsConfigCaptcha;
		// Loading RUN-TIME settings
		foreach (array_keys($this->config) as $key) {
			if (isset(${$key}) && ${$key} !== null) {
				$this->config[$key] = ${$key};
			}
		}
		$this->config["name"] = $name;

		// Skip CAPTCHA for group
		//$gperm_handler = & xoops_gethandler('member_groupperm');
		$icmsUser = $GLOBALS["icmsUser"];
		$groups = is_object($icmsUser) ? $icmsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
		if (array_intersect($groups, $icmsConfigCaptcha['captcha_skipmember']) && is_object($GLOBALS["xoopsUser"])) {
			$this->active = false;
		} elseif ($icmsConfigCaptcha['captcha_mode'] =='none') {
			$this->active = false;
		}
	}

	/**
	 * Verify user submission
	 * @param bool	$skipMember	Skip Captcha because user is member / logged in
	 */
	function verify($skipMember = null)
	{
		global $icmsConfig, $icmsConfigCaptcha;
		$sessionName	= @$_SESSION['icms_captcha_Object_name'];
		$skipMember		= ($skipMember === null) ? @$_SESSION['icms_captcha_Object_skipmember'] : $skipMember;
		$maxAttempts	= (int) ( @$_SESSION['icms_captcha_Object_maxattempts'] );

		$is_valid = false;

		// Skip CAPTCHA for member if set & Kept for backward compatibilities
		/*		if (is_object($GLOBALS["xoopsUser"]) && !empty($skipMember)) {
			$is_valid = true;
			*/
		// Kill too many attempts
		/*} else*/
		$icmsUser = $GLOBALS["xoopsUser"];
		$groups = is_object($icmsUser) ? $icmsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
		if (array_intersect($groups, $icmsConfigCaptcha['captcha_skipmember']) && is_object($icmsUser)) {
			$is_valid = true;
		} elseif (!empty($maxAttempts) && $_SESSION['icms_captcha_Object_attempt_'.$sessionName] > $maxAttempts) {
			$this->message[] = ICMS_CAPTCHA_TOOMANYATTEMPTS;

			// Verify the code
		} elseif (!empty($_SESSION['icms_captcha_Object_sessioncode'])) {
			$func = ($icmsConfigCaptcha['captcha_casesensitive']) ? "strcmp" : "strcasecmp";
			$is_valid = ! $func( trim(@$_POST[$sessionName]), $_SESSION['icms_captcha_Object_sessioncode']);
		}

		if (!empty($maxAttempts)) {
			if (!$is_valid) {
				// Increase the attempt records on failure
				$_SESSION['icms_captcha_Object_attempt_'.$sessionName]++;
				// Log the error message
				$this->message[] = ICMS_CAPTCHA_INVALID_CODE;

			} else {
				// reset attempt records on success
				$_SESSION['icms_captcha_Object_attempt_'.$sessionName] = null;
			}
		}

		$this->destroyGarbage(true);

		return $is_valid;
	}

	/**
	 * Get Caption
	 * @return string	The Caption Constant
	 */
	function getCaption()
	{
		return defined("ICMS_CAPTCHA_CAPTION") ? constant("ICMS_CAPTCHA_CAPTION") : "";
	}

	/**
	 * Set Message
	 * @return string	The message
	 */
	function getMessage()
	{
		return implode("<br />", $this->message);
	}

	/**
	 * Destory historical stuff
	 * @param bool	$clearSession	also clear session variables?
	 * @return bool True if destroying succeeded
	 */
	function destroyGarbage($clearSession = false)
	{
		$class = "icms_captcha_".ucfirst($this->mode);
		$captcha_handler = new $class();
		if (method_exists($captcha_handler, "destroyGarbage")) {
			$captcha_handler->loadConfig($this->config);
			$captcha_handler->destroyGarbage();
		}

		if ($clearSession) {
			$_SESSION['icms_captcha_Object_name'] = null;
			$_SESSION['icms_captcha_Object_skipmember'] = null;
			$_SESSION['icms_captcha_Object_sessioncode'] = null;
			$_SESSION['icms_captcha_Object_maxattempts'] = null;
		}

		return true;
	}

	/**
	 * Render
	 * @return  string  the rendered form
	 */
	function render()
	{
		global $icmsConfigCaptcha;
		$form = "";

		if (!$this->active || empty($this->config["name"])) {
			return $form;
		}
		$_SESSION['icms_captcha_Object_name'] = $this->config["name"];
		$_SESSION['icms_captcha_Object_skipmember'] = $icmsConfigCaptcha['captcha_skipmember'];
		$maxAttempts = $icmsConfigCaptcha['captcha_maxattempt'];
		$_SESSION['icms_captcha_Object_maxattempts'] = $maxAttempts;

		 if (!empty($maxAttempts)) {
			$_SESSION['icms_captcha_Object_maxattempts_'.$_SESSION['icms_captcha_Object_name']] = $maxAttempts;
			}


		// Fail on too many attempts
		if (!empty($maxAttempts) && @$_SESSION['icms_captcha_Object_attempt_'.$this->config["name"]] > $maxAttempts) {
			$form = ICMS_CAPTCHA_TOOMANYATTEMPTS;
			// Load the form element
		} else {
			$form = $this->loadForm();
		}

		return $form;
	}

	/**
	 * Load Form
	 * @return string	The Loaded Captcha Form
	 */
	function loadForm()
	{
		$class = "icms_captcha_".ucfirst($this->mode);
		$captcha_handler = new $class();
		$captcha_handler->loadConfig($this->config);

		$form = $captcha_handler->render();
		return $form;
	}
}
?>