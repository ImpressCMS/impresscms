<?php
/**
 * CAPTCHA class For XOOPS
 *
 * Currently there are two types of CAPTCHA forms, text and image
 * The default mode is "text", it can be changed in the priority:
 * 1 If mode is set through IcmsFormCaptcha::setConfig("mode", $mode), take it
 * 2 Elseif mode is set though captcha/config.php, take it
 * 3 Else, take "text"
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		installer
 * @since		XOOPS
 * @author		http://www.xoops.org/ The XOOPS Project
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @author	   Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		$Id:$
*/


if(! @include_once ICMS_ROOT_PATH."/language/".$GLOBALS["xoopsConfig"]["language"]."/captcha.php") {
	require_once ICMS_ROOT_PATH."/language/english/captcha.php";
}



class IcmsCaptcha {
	var $active	= true;
	var $mode 	= "text";	// potential values: image, text
	var $config	= array();

	var $message = array(); // Logging error messages


	/**
	 * Constructor
	 */
	function IcmsCaptcha()
	{
		// Loading default preferences
		$this->config = @include dirname(__FILE__)."/config.php";

  	$config_handler =& xoops_gethandler('config');
  	$IcmsConfigCaptcha =& $config_handler->getConfigsByCat(ICMS_CONF_CAPTCHA);
		$this->setMode($IcmsConfigCaptcha['captcha_mode']);
	}


	/**
	 * Creates instance of IcmsCaptcha Object
   * @return  object Reference to the IcmsCaptcha Object
	 */
	function &instance()
	{
		static $instance;
		if(!isset($instance)) {
			$instance =& new IcmsCaptcha();
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
		if($name == "mode") {
			$this->setMode($val);
		}elseif(isset($this->$name)) {
			$this->$name = $val;
		}else {
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
		if( !empty($mode) && in_array($mode, array("text", "image")) ) {
			$this->mode = $mode;

			if($this->mode != "image") {
				return;
			}
		}

		// Disable image mode
		if(!extension_loaded('gd')) {
			$this->mode = "text";
		}else{
			$required_functions = array("imagecreatetruecolor", "imagecolorallocate", "imagefilledrectangle", "imagejpeg", "imagedestroy", "imageftbbox");
			foreach($required_functions as $func) {
				if(!function_exists($func)) {
					$this->mode = "text";
					break;
				}
			}
		}

	}





	/**
	 * Initializing the CAPTCHA class
   * @param   string  $name             name of the instance
   * @param   string  $skipmember       Skip the captcha because the user is member / logged in
   * @param   string  $num_chars        comes from config, just initializes the variable
   * @param   string  $fontsize_min     comes from config, just initializes the variable
   * @param   string  $fontsize_max     comes from config, just initializes the variable
   * @param   string  $background_type  comes from config, just initializes the variable
   * @param   string  $background_num   comes from config, just initializes the variable
	 */
	function init($name = 'icmscaptcha', $skipmember = null, $num_chars = null, $fontsize_min = null, $fontsize_max = null, $background_type = null, $background_num = null)
	{
		$config_handler =& xoops_gethandler('config');
		$IcmsConfigCaptcha =& $config_handler->getConfigsByCat(ICMS_CONF_CAPTCHA);
		// Loading RUN-TIME settings
		foreach(array_keys($this->config) as $key) {
			if(isset(${$key}) && ${$key} !== null) {
				$this->config[$key] = ${$key};
			}
		}
		$this->config["name"] = $name;

		// Skip CAPTCHA for group
		$gperm_handler = & xoops_gethandler( 'groupperm' );
		$xoopsUser = $GLOBALS["xoopsUser"];
		$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
		if(array_intersect($groups, $IcmsConfigCaptcha['captcha_skipmember']) && is_object($GLOBALS["xoopsUser"])) {
			$this->active = false;
		}elseif($IcmsConfigCaptcha['captcha_mode'] =='none'){
			$this->active = false;
		}
	}







	/**
	 * Verify user submission
	 * @param bool	$skipMember	Skip Captcha because user is member / logged in
	 */
	function verify($skipMember = null)
	{
		$config_handler =& xoops_gethandler('config');
		$IcmsConfigCaptcha =& $config_handler->getConfigsByCat(ICMS_CONF_CAPTCHA);
		$sessionName	= @$_SESSION['IcmsCaptcha_name'];
		$skipMember		= ($skipMember === null) ? @$_SESSION['IcmsCaptcha_skipmember'] : $skipMember;
		$maxAttempts	= intval( @$_SESSION['IcmsCaptcha_maxattempts'] );

		$is_valid = false;

		// Skip CAPTCHA for member if set & Kept for backward compatibilities
/*		if( is_object($GLOBALS["xoopsUser"]) && !empty($skipMember) ) {
			$is_valid = true;
*/
		// Kill too many attempts
		/*}else*/if(!empty($maxAttempts) && $_SESSION['IcmsCaptcha_attempt_'.$sessionName] > $maxAttempts) {
			$this->message[] = ICMS_CAPTCHA_TOOMANYATTEMPTS;

		// Verify the code
		}elseif(!empty($_SESSION['IcmsCaptcha_sessioncode'])){
			$func = ($IcmsConfigCaptcha['captcha_casesensitive']) ? "strcmp" : "strcasecmp";
			$is_valid = ! $func( trim(@$_POST[$sessionName]), $_SESSION['IcmsCaptcha_sessioncode']);
		}

		if(!empty($maxAttempts)) {
			if(!$is_valid) {
				// Increase the attempt records on failure
				$_SESSION['IcmsCaptcha_attempt_'.$sessionName]++;
				// Log the error message
				$this->message[] = ICMS_CAPTCHA_INVALID_CODE;

			}else{
				// reset attempt records on success
				$_SESSION['IcmsCaptcha_attempt_'.$sessionName] = null;
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
		require_once dirname(__FILE__)."/".$this->mode.".php";
		$class = "IcmsCaptcha".ucfirst($this->mode);
		$captcha_handler =& new $class();
		if(method_exists($captcha_handler, "destroyGarbage")) {
			$captcha_handler->loadConfig($this->config);
			$captcha_handler->destroyGarbage();
		}

		if($clearSession) {
			$_SESSION['IcmsCaptcha_name'] = null;
			$_SESSION['IcmsCaptcha_skipmember'] = null;
			$_SESSION['IcmsCaptcha_sessioncode'] = null;
			$_SESSION['IcmsCaptcha_maxattempts'] = null;
		}

		return true;
	}





	/**
	 * Render
   * @return  string  the rendered form
	 */
	function render()
	{
		$config_handler =& xoops_gethandler('config');
		$IcmsConfigCaptcha =& $config_handler->getConfigsByCat(ICMS_CONF_CAPTCHA);
		$form = "";

		if( !$this->active || empty($this->config["name"]) ) {
			return $form;
		}

		$_SESSION['IcmsCaptcha_name'] = $this->config["name"];
		$_SESSION['IcmsCaptcha_skipmember'] = $IcmsConfigCaptcha['captcha_skipmember'];
		$maxAttempts = $IcmsConfigCaptcha['captcha_maxattempt'];
		$_SESSION['IcmsCaptcha_maxattempts'] = $maxAttempts;
		/*
		if(!empty($maxAttempts)) {
			$_SESSION['IcmsCaptcha_maxattempts_'.$_SESSION['IcmsCaptcha_name']] = $maxAttempts;
		}
		*/

		// Fail on too many attempts
		if(!empty($maxAttempts) && @$_SESSION['IcmsCaptcha_attempt_'.$this->config["name"]] > $maxAttempts) {
			$form = ICMS_CAPTCHA_TOOMANYATTEMPTS;
		// Load the form element
		}else{
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
		require_once dirname(__FILE__)."/".$this->mode.".php";
		$class = "IcmsCaptcha".ucfirst($this->mode);
		$captcha_handler =& new $class();
		$captcha_handler->loadConfig($this->config);

		$form = $captcha_handler->render();
		return $form;
	}
}
?>