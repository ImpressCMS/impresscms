<?php
/**
* Form control creating an image upload element for an object derived from IcmsPersistableObject
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		IcmsPersistableObject
* @since		1.1
* @author		marcan <marcan@impresscms.org>
* @version		$Id: icmsformlanguageelement.php 1889 2008-04-30 15:54:09Z malanciault $
*/

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

class IcmsFormLanguageElement extends XoopsFormSelectLang {

    function IcmsFormLanguageElement($object, $key) {

        $var = $object->vars[$key];
        $control = $object->controls[$key];
        $all = isset($control['all']) ? true : false;

		$this->XoopsFormSelectLang($var['form_caption'], $key, $object->getVar($key, 'e'));
		if ($all) {
			$this->addOption('all', _ALL);
		}
    }
}
?>