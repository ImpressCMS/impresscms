<?php
/**
* Form control creating an hidden field for an object derived from IcmsPersistableObject
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		IcmsPersistableObject
* @since		1.1
* @author		marcan <marcan@impresscms.org>
* @version		$Id: icmsformhidden.php 1889 2008-04-30 15:54:09Z malanciault $
*/

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

class IcmsFormHidden extends XoopsFormHidden {

	function render(){
		if(is_array($this->getValue())){
			$ret = '';
			foreach($this->getValue() as $value){
     			$ret .= "<input type='hidden' name='".$this->getName()."[]' id='".$this->getName()."' value='".$value."' />\n";
            }
		}else{
			$ret = "<input type='hidden' name='".$this->getName()."' id='".$this->getName()."' value='".$this->getValue()."' />";
		}
		return $ret;
	}
}

?>
