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
 * @package		FormCaptcha
 * @since		XOOPS
 * @author		http://www.xoops.org/ The XOOPS Project
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @author	   Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		$Id$
*/


icms_loadLanguageFile('core', 'captcha');
class IcmsCaptcha {
	public $active	= true;
	public $mode 	= "text";	// potential values: image, text
	public $message = array(); // Logging error messages
	public $config = array();


	/**
	 * Constructor
	 */
	function IcmsCaptcha()
	{
		// Loading default preferences
		$this->config = array('name' => 'icmscaptcha');
		global $icmsConfigCaptcha;
		$this->setMode(str_replace('.php', '', $icmsConfigCaptcha['captcha_mode']));
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
		if( !empty($mode) && in_array($mode, array("text", "image", "recaptcha")) ) {
			$this->mode = $mode;

			if($this->mode != "image" || $this->mode != "recaptcha") {
				return;
			}
		}

		// Disable image mode
		global $icmsConfigPersona;
		if($mode == "image"){
			$required_functions = array("gd", "imagecreatetruecolor", "imagecolorallocate", "imagefilledrectangle", "imagejpeg", "imagedestroy", "imageftbbox");
			foreach($required_functions as $func) {
				if(!function_exists($func)) {
					$this->mode = "text";
					break;
				}
			}
		}elseif($mode == "recaptcha" && extension_loaded('mcrypt_encrypt') && isset($icmsConfigPersona['recprvkey']) && $icmsConfigPersona['recprvkey'] != '' && isset($icmsConfigPersona['recpubkey']) && $icmsConfigPersona['recpubkey'] != ''){
			$this->mode = $mode;
		}else{
			$this->mode = "text";
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
		foreach(array_keys($this->config) as $key) {
			if(isset(${$key}) && ${$key} !== null) {
				$this->config[$key] = ${$key};
			}
		}
		$this->config["name"] = $name;

		// Skip CAPTCHA for group
		//$gperm_handler = & xoops_gethandler( 'groupperm' );
		$icmsUser = $GLOBALS["icmsUser"];
		$groups = is_object($icmsUser) ? $icmsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
		if(array_intersect($groups, $icmsConfigCaptcha['captcha_skipmember']) && is_object($GLOBALS["icmsUser"])) {
			$this->active = false;
		}elseif($this->mode == 'none'){
			$this->active = false;
		}
	}

	/**
	 * Destory historical stuff
	 * @param bool	$clearSession	also clear session variables?
   * @return bool True if destroying succeeded
	 */
	function destroyGarbage($clearSession = false){
		$_SESSION['IcmsCaptcha_name'] = null;
		$_SESSION['IcmsCaptcha_skipmember'] = null;
		$_SESSION['IcmsCaptcha_sessioncode'] = null;
		$_SESSION['IcmsCaptcha_maxattempts'] = null;
	}

	/**
	 * Verify user submission
	 * @param bool	$skipMember	Skip Captcha because user is member / logged in
	 */
	function verify($skipMember = null)
	{
		global $icmsConfig, $icmsConfigCaptcha;
		$sessionName	= @$_SESSION['IcmsCaptcha_name'];
		$skipMember		= ($skipMember === null) ? @$_SESSION['IcmsCaptcha_skipmember'] : $skipMember;
		$maxAttempts	= intval( @$_SESSION['IcmsCaptcha_maxattempts'] );

		$is_valid = false;

		// Skip CAPTCHA for member if set & Kept for backward compatibilities
/*		if( is_object($GLOBALS["icmsUser"]) && !empty($skipMember) ) {
			$is_valid = true;
*/
		// Kill too many attempts
		/*}else*/
        include_once ICMS_ROOT_PATH . '/kernel/icmsstopspammer.php';
        $icmsStopSpammers = new IcmsStopSpammer();
		$icmsUser = $GLOBALS["icmsUser"];
		$groups = is_object($icmsUser) ? $icmsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
		if(array_intersect($groups, $icmsConfigCaptcha['captcha_skipmember']) && is_object($icmsUser)) {
			$is_valid = true;
		}elseif($icmsConfig['enable_badips']){
			foreach ($icmsConfig['bad_ips'] as $bi) {
				if (!empty($bi) && preg_match("/".$bi."/", $_SERVER['REMOTE_ADDR'])) {
					$is_valid = false;
				}
			}
			unset($bi);
        }elseif($icmsStopSpammers->badIP($_SERVER['REMOTE_ADDR'])){
            $is_valid = false;
		}elseif(!empty($maxAttempts) && $_SESSION['IcmsCaptcha_attempt_'.$sessionName] > $maxAttempts) {
			$this->message[] = ICMS_CAPTCHA_TOOMANYATTEMPTS;

		// Verify the code
		}elseif(!empty($_SESSION['IcmsCaptcha_sessioncode']) && $this->mode != 'recaptcha'){
			$func = ($icmsConfigCaptcha['captcha_casesensitive']) ? "strcmp" : "strcasecmp";
			$is_valid = ! $func( trim(@$_POST[$sessionName]), $_SESSION['IcmsCaptcha_sessioncode']);
		}elseif($this->mode == 'recaptcha'){
			global $icmsConfigPersona;
			require_once(ICMS_LIBRARIES_PATH.'/recaptcha/recaptchalib.php');
			$resp = recaptcha_check_answer ($icmsConfigPersona['recprvkey'],
								$_SERVER["REMOTE_ADDR"],
								$_POST["recaptcha_challenge_field"],
								$_POST["recaptcha_response_field"]);
			$is_valid = $resp->is_valid;
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

		$this->destroyGarbage();

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
	 * Render
   * @return  string  the rendered form
	 */
	function render()
	{
		global $icmsConfigCaptcha;
		$form = "";

		if( !$this->active || empty($this->config["name"]) ) {
			return $form;
		}

		$_SESSION['IcmsCaptcha_name'] = $this->config["name"];
		$_SESSION['IcmsCaptcha_skipmember'] = $icmsConfigCaptcha['captcha_skipmember'];
		$maxAttempts = $icmsConfigCaptcha['captcha_maxattempt'];
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
		require_once dirname(__FILE__)."/plugins/".$this->mode.".php";
		$class = "IcmsCaptcha".ucfirst($this->mode);
		$captcha_handler =& new $class();

		$form = $captcha_handler->render();
		return $form;
	}
}
class XoopsCaptcha extends IcmsCaptcha { /* For backwards compatibility */ }
?>