<?php
/**
* Form control creating a textbox to enter time for an object derived from IcmsPersistableObject
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		IcmsPersistableObject
* @since		1.1
* @author		marcan <marcan@impresscms.org>
* @version		$Id$
*/

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

class IcmsFormTimeElement extends XoopsFormSelect {
    function IcmsFormTimeElement($object, $key) {
		$var = $object->vars[$key];
		$timearray = array();
		for ($i = 0; $i < 24; $i++) {
			for ($j = 0; $j < 60; $j = $j + 10) {
				$key_t = ($i * 3600) + ($j * 60);
				$timearray[$key_t] = ($j != 0) ? $i.':'.$j : $i.':0'.$j;
			}
		}
		ksort($timearray);
		$this->XoopsFormSelect($var['form_caption'], $key, $object->getVar($key, 'e'));
		$this->addOptionArray($timearray);
    }
}
?>