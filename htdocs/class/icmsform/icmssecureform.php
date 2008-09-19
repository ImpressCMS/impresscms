<?php
if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

/**
 * Including the IcmsForm class
 */
include_once ICMS_ROOT_PATH . '/class/icmsform/icmsform.php';

/**
* IcmsSecureForm extending IcmsForm with the addition of the Security Token
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		IcmsPersistableObject
* @since		1.1
* @author		marcan <marcan@impresscms.org>
* @version		$Id: icmsform.php 4847 2008-09-14 15:50:58Z malanciault $
*/
class IcmsSecureForm extends IcmsForm {

	function IcmsSecureForm(&$target, $form_name, $form_caption, $form_action, $form_fields=null, $submit_button_caption = false, $cancel_js_action=false, $captcha=false) {
		parent::IcmsForm(&$target, $form_name, $form_caption, $form_action, $form_fields=null, $submit_button_caption = false, $cancel_js_action=false, $captcha=false);
		$this->addElement(new XoopsFormHiddenToken());
	}
}
?>