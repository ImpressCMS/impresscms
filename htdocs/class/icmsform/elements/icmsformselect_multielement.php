<?php
/**
* Form control creating a multi selectbox for an object derived from IcmsPersistableObject
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		IcmsPersistableObject
* @since		1.1
* @author		marcan <marcan@impresscms.org>
* @version		$Id: icmsformselect_multielement.php 1889 2008-04-30 15:54:09Z malanciault $
*/

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

include_once (ICMS_ROOT_PATH . "/class/icmsform/elements/icmsformselectelement.php");

class IcmsFormSelect_multiElement extends IcmsFormSelectElement  {
    function IcmsFormSelect_multiElement($object, $key) {
        $this->multiple = true;
        parent::IcmsFormSelectElement($object, $key);
    }
}
?>