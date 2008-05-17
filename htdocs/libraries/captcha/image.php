<?php
/**
 * Image Creation class for CAPTCHA
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

class XoopsCaptchaImage {
	var $config	= array();
	
	function XoopsCaptchaImage()
	{
		//$this->name = md5( session_id() );
	}
	
	function &instance()
	{
		static $instance;
		if(!isset($instance)) {
			$instance =& new XoopsCaptchaImage();
		}
		return $instance;
	}
	
	/**
	 * Loading configs from CAPTCHA class
	 */
	function loadConfig($config = array())
	{
		// Loading default preferences
		$this->config =& $config;
	}

	function render()
	{
		$form = "<input type='text' name='".$this->config["name"]."' id='".$this->config["name"]."' size='" . $this->config["num_chars"] . "' maxlength='" . $this->config["num_chars"] . "' value='' /> &nbsp; ". $this->loadImage();
		$rule = htmlspecialchars(ICMS_CAPTCHA_REFRESH, ENT_QUOTES);
		if($this->config["maxattempt"]) {
			$rule .=  " | ". sprintf( constant("ICMS_CAPTCHA_MAXATTEMPTS"), $this->config["maxattempt"] );
		}
		$form .= "&nbsp;&nbsp;<small>{$rule}</small>";
		
		return $form;
	}


	function loadImage()
	{
		$rule = $this->config["casesensitive"] ? constant("ICMS_CAPTCHA_RULE_CASESENSITIVE") : constant("ICMS_CAPTCHA_RULE_CASEINSENSITIVE");
		$ret = "<img id='captcha' src='" . ICMS_URL. "/". $this->config["imageurl"]. "' onclick=\"this.src='" . ICMS_URL. "/". $this->config["imageurl"]. "?refresh='+Math.random()"."\" align='absmiddle'  style='cursor: pointer;' alt='".htmlspecialchars($rule, ENT_QUOTES)."' />";
		
		return $ret;
	}

}
?>