<?php
/**
* Form control creating a user signature textarea for an object derived from IcmsPersistableObject
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		IcmsPersistableObject
* @since		1.1
* @author		marcan <marcan@impresscms.org>
* @version		$Id: icmsformuser_sigelement.php 1889 2008-04-30 15:54:09Z malanciault $
*/

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

class IcmsFormUser_sigElement extends XoopsFormElementTray {

	function IcmsFormUser_sigElement($object, $key){

	    $var = $object->vars[$key];
	    $control = $object->controls[$key];

     	$this->XoopsFormElementTray($var['form_caption'], '<br /><br />', $key . '_signature_tray');

		$signature_textarea = new XoopsFormDhtmlTextArea('', $key, $object->getVar($key, 'e'));
		$this->addElement($signature_textarea);

		$attach_checkbox = new XoopsFormCheckBox('', 'attachsig', $object->getVar('attachsig', 'e'));
		$attach_checkbox->addOption(1, _US_SHOWSIG);
		$this->addElement($attach_checkbox);
	}
}
?>