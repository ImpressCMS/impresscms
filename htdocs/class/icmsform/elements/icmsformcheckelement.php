<?php
/**
* Form control creating a checkbox element for an object derived from IcmsPersistableObject
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		IcmsPersistableObject
* @since		1.1
* @author		marcan <marcan@impresscms.org>
* @version		$Id$
*/

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

class IcmsFormCheckElement extends XoopsFormCheckBox {

	/**

	/**
	* prepare HTML for output
	*
	* @return	string
	*/
	function render(){
		$ret = "";
		if ( count($this->getOptions()) > 1 && substr($this->getName(), -2, 2) != "[]" ) {
			$newname = $this->getName()."[]";
			$this->setName($newname);
		}
		foreach ( $this->getOptions() as $value => $name ) {
			$ret .= "<input type='checkbox' name='".$this->getName()."' value='".$value."'";
			if (count($this->getValue()) > 0 && in_array($value, $this->getValue())) {
				$ret .= " checked='checked'";
			}
			$ret .= $this->getExtra()." />".$name."<br/>";
		}
		return $ret;
	}
	function renderValidationJS(){
		$js .= "var hasSelections = false;";
		//sometimes, there is an implicit '[]', sometimes not
		$eltname = $this->getName();
		if(strpos($eltname, '[') === false){
			$js .= "for(var i = 0; i < myform['{$eltname}[]'].length; i++){
				if (myform['{$eltname}[]'][i].checked) {
					hasSelections = true;
				}

			}
			if (hasSelections == false) {
				window.alert(\"{$eltmsg}\"); myform['{$eltname}[]'][0].focus(); return false; }\n";
		}else{
			$js .= "for(var i = 0; i < myform['{$eltname}'].length; i++){
				if (myform['{$eltname}'][i].checked) {
					hasSelections = true;
				}

			}
			if (hasSelections == false) {
				window.alert(\"{$eltmsg}\"); myform['{$eltname}'][0].focus(); return false; }\n";
		}
		return $js;
	}

}
?>