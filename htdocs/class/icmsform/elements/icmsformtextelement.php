<?php
/**
* Form control creating a textbox for an object derived from IcmsPersistableObject
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		IcmsPersistableObject
* @since		1.1
* @author		marcan <marcan@impresscms.org>
* @version		$Id: icmsformtextelement.php 1889 2008-04-30 15:54:09Z malanciault $
*/

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

class IcmsFormTextElement extends XoopsFormText {
    function IcmsFormTextElement($object, $key) {
		$var = $object->vars[$key];

		if(isset($object->controls[$key])){
			$control = $object->controls[$key];
	        $form_maxlength = isset($control['maxlength']) ? $control['maxlength'] : (isset($var['maxlength']) ? $var['maxlength'] : 255);
			$form_size = isset($control['size']) ? $control['size'] : 50;
		}else{
			$form_maxlength =  255;
			$form_size = 50;
		}

		$this->XoopsFormText($var['form_caption'], $key, $form_size, $form_maxlength, $object->getVar($key, 'e'));
    }
}
?>