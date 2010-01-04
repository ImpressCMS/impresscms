<?php
/**
 * Recaptcha form for CAPTCHA
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		FormCaptcha
 * @since		1.3
 * @author	   Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		$Id$
*/

class IcmsCaptchaRecaptcha {

	/**
	 * Constructor
	 */
	function IcmsCaptchaRecaptcha()
	{
	}

	/**
	 * Creates IcmsCaptchaRecaptcha object
	 * @return object	reference to IcmsCaptchaRecaptcha (@link IcmsCaptchaRecaptcha) Object
	 */
	function &instance()
	{
		static $instance;
		if(!isset($instance)) {
			$instance =& new IcmsCaptchaRecaptcha();
		}
		return $instance;
	}

	/**
	 * Render the form
	 * @return string	$form the Captcha Form
	 */
	function render(){
		global $icmsConfigPersona;
		require_once(ICMS_LIBRARIES_PATH.'/recaptcha/recaptchalib.php');
		$form = recaptcha_get_html($icmsConfigPersona['recpubkey']);
		return $form;
	}

}
?>