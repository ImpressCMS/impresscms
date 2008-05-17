<?php
/**
 * CAPTCHA class For XOOPS
 *
 * Currently there are two types of CAPTCHA forms, text and image
 * The default mode is "text", it can be changed in the priority:
 * 1 If mode is set through XoopsFormCaptcha::setConfig("mode", $mode), take it
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
 * @author		modified by Sina Asghari <stranger@impresscms.ir>
 * @version		$Id:$
*/


class XoopsCaptcha {
	var $active	= true;
	var $mode 	= "text";	// potential values: image, text
	var $config	= array();
	
	var $message = array(); // Logging error messages
	
	function XoopsCaptcha()
	{
		// Loading default preferences
		$this->config = @include dirname(__FILE__)."/config.php";
		
		$this->setMode($this->config["mode"]);
	}
	
	function &instance()
	{
		static $instance;
		if(!isset($instance)) {
			$instance =& new XoopsCaptcha();
		}
		return $instance;
	}
	
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
	 */
	function init($name = 'xoopscaptcha', $skipmember = null, $num_chars = null, $fontsize_min = null, $fontsize_max = null, $background_type = null, $background_num = null)
	{
		// Loading RUN-TIME settings
		foreach(array_keys($this->config) as $key) {
			if(isset(${$key}) && ${$key} !== null) {
				$this->config[$key] = ${$key};
			}
		}
		$this->config["name"] = $name;
		
		// Skip CAPTCHA for member if set
		if($this->config["skipmember"] && is_object($GLOBALS["xoopsUser"])) {
			$this->active = false;
		}
	}
	
	/** 
	 * Verify user submission
	 */
	function verify($skipMember = null) 
	{
		$sessionName	= @$_SESSION['XoopsCaptcha_name'];
		$skipMember		= ($skipMember === null) ? @$_SESSION['XoopsCaptcha_skipmember'] : $skipMember;
		$maxAttempts	= intval( @$_SESSION['XoopsCaptcha_maxattempts'] );
		
		$is_valid = false;
		
		// Skip CAPTCHA for member if set
		if( is_object($GLOBALS["xoopsUser"]) && !empty($skipMember) ) {
			$is_valid = true;
			
		// Kill too many attempts
		}elseif(!empty($maxAttempts) && $_SESSION['XoopsCaptcha_attempt_'.$sessionName] > $maxAttempts) {
			$this->message[] = ICMS_CAPTCHA_TOOMANYATTEMPTS;
		
		// Verify the code
		}elseif(!empty($_SESSION['XoopsCaptcha_sessioncode'])){
			$func = ($this->config["casesensitive"]) ? "strcmp" : "strcasecmp";
			$is_valid = ! $func( trim(@$_POST[$sessionName]), $_SESSION['XoopsCaptcha_sessioncode']);
		}
		
		if(!empty($maxAttempts)) {
			if(!$is_valid) {
				// Increase the attempt records on failure
				$_SESSION['XoopsCaptcha_attempt_'.$sessionName]++;
				// Log the error message
				$this->message[] = ICMS_CAPTCHA_INVALID_CODE;
				
			}else{
				// reset attempt records on success
				$_SESSION['XoopsCaptcha_attempt_'.$sessionName] = null;
			}
		}
		
		$this->destroyGarbage(true);
		
		return $is_valid;
	}
	
	function getCaption()
	{
		return defined("ICMS_CAPTCHA_CAPTION") ? constant("ICMS_CAPTCHA_CAPTION") : "";
	}
	
	function getMessage()
	{
		return implode("<br />", $this->message);
	}

	/**
	 * Destory historical stuff
	 */
	function destroyGarbage($clearSession = false) 
	{
		require_once dirname(__FILE__)."/".$this->mode.".php";
		$class = "XoopsCaptcha".ucfirst($this->mode);
		$captcha_handler =& new $class();
		if(method_exists($captcha_handler, "destroyGarbage")) {
			$captcha_handler->loadConfig($this->config);
			$captcha_handler->destroyGarbage();
		}
		
		if($clearSession) {
			$_SESSION['XoopsCaptcha_name'] = null;
			$_SESSION['XoopsCaptcha_skipmember'] = null;
			$_SESSION['XoopsCaptcha_sessioncode'] = null;
			$_SESSION['XoopsCaptcha_maxattempts'] = null;
		}
		
		return true;
	}

	function render()
	{
		$form = "";
		
		if( !$this->active || empty($this->config["name"]) ) {
			return $form;
		}
		
		$_SESSION['XoopsCaptcha_name'] = $this->config["name"];
		$_SESSION['XoopsCaptcha_skipmember'] = $this->config["skipmember"];
		$maxAttempts = $this->config["maxattempt"];
		$_SESSION['XoopsCaptcha_maxattempts'] = $maxAttempts;
		/*
		if(!empty($maxAttempts)) {
			$_SESSION['XoopsCaptcha_maxattempts_'.$_SESSION['XoopsCaptcha_name']] = $maxAttempts;
		}
		*/
		
		// Fail on too many attempts
		if(!empty($maxAttempts) && @$_SESSION['XoopsCaptcha_attempt_'.$this->config["name"]] > $maxAttempts) {
			$form = ICMS_CAPTCHA_TOOMANYATTEMPTS;
		// Load the form element
		}else{
			$form = $this->loadForm();
		}
		
		return $form;
	}
	
	function loadForm()
	{
		require_once dirname(__FILE__)."/".$this->mode.".php";
		$class = "XoopsCaptcha".ucfirst($this->mode);
		$captcha_handler =& new $class();
		$captcha_handler->loadConfig($this->config);
		
		$form = $captcha_handler->render();
		return $form;
	}
}
?>