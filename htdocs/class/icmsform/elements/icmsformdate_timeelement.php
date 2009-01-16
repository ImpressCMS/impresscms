<?php

/**
* Form control creating a DateTime Picker element for an object derived from IcmsPersistableObject
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		IcmsPersistableObject
* @since		1.1
* @author		marcan <marcan@impresscms.org>
* @version		$Id$
*/

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

class IcmsFormDate_timeElement extends XoopsFormDateTime {
    function IcmsFormDate_timeElement($object, $key) {
        $this->XoopsFormDateTime($object->vars[$key]['form_caption'], $key, 15, $object->getVar($key, 'e'));
    }
}
?>