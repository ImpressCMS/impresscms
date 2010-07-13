<?php
/**
 * Adding CAPTCHA
 *
 * Currently there are two types of CAPTCHA forms, text and image
 * The default mode is "text", it can be changed in the priority:
 * 1 If mode is set through IcmsFormCaptcha::setMode(), take it
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
 * @version		$Id: formcaptcha.php 8685 2009-05-02 15:00:58Z pesianstranger $
 */

if (!defined('ICMS_ROOT_PATH')) {
	die("ImpressCMS root path not defined");
}

/*
 * Usage
 *
 * For form creation:
 * 1 Add [include_once ICMS_ROOT_PATH."/class/captcha/formcaptcha.php";] to class/xoopsformloader.php, OR add to the file that uses CAPTCHA before calling IcmsFormCaptcha
 * 2 Add form element where proper: $xoopsform->addElement(new IcmsFormCaptcha($caption, $name, $skipmember, ...);
 *
 * For verification:
 *   if(@include_once ICMS_ROOT_PATH."/class/captcha/captcha.php") {
 *	    $icmsCaptcha = icms_captcha_Object::instance();
 *	    if(! $icmsCaptcha->verify() ) {
 *		    echo $icmsCaptcha->getMessage();
 *		    ...
 *	    }
 *  }
 *
 */

class IcmsFormCaptcha extends icms_form_elements_captcha {
	private $_deprecated;
	public function __construct() {
		parent::__construct();
		$this->_deprecated = icms_core_Debug::setDeprecated('icms_form_elements_captcha', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}

class XoopsFormCaptcha extends IcmsFormCaptcha { /* For backwards compatibility */ }

?>