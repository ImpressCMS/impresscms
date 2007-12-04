<?php
// $Id: formcheckbox.php 1029 2007-09-09 03:49:25Z phppp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}
/**
 * @package     kernel
 * @subpackage  form
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
/**
 * One or more Checkbox(es)
 *
 * @package     kernel
 * @subpackage  form
 *
 * @author	Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class XoopsFormCheckBox extends XoopsFormElement {

	/**
     * Availlable options
	 * @var array
	 * @access	private
	 */
	var $_options = array();

	/**
     * pre-selected values in array
	 * @var	array
	 * @access	private
	 */
	var $_value = array();

	/**
	 * Constructor
	 *
     * @param	string  $caption
     * @param	string  $name
     * @param	mixed   $value  Either one value as a string or an array of them.
	 */
	function XoopsFormCheckBox($caption, $name, $value = null, $delimeter = ""){
		$this->setCaption($caption);
		$this->setName($name);
		if (isset($value)) {
			$this->setValue($value);
		}
		$this->delimeter = $delimeter;
	}

	/**
	 * Get the "value"
	 *
     * @return	array
	 */
	function getValue(){
		return $this->_value;
	}

	/**
	 * Set the "value"
	 *
     * @param	array
	 */
	function setValue($value){
		$this->_value = array();
		if (is_array($value)) {
			foreach ($value as $v) {
				$this->_value[] = $v;
			}
		} else {
			$this->_value[] = $value;
		}
	}

	/**
	 * Add an option
	 *
     * @param	string  $value
     * @param	string  $name
	 */
	function addOption($value, $name=""){
		if ($name != "") {
			$this->_options[$value] = $name;
		} else {
			$this->_options[$value] = $value;
		}
	}

	/**
	 * Add multiple Options at once
	 *
     * @param	array   $options    Associative array of value->name pairs
	 */
	function addOptionArray($options){
		if ( is_array($options) ) {
			foreach ( $options as $k=>$v ) {
				$this->addOption($k, $v);
			}
		}
	}

	/**
	 * Get an array with all the options
	 *
     * @return	array   Associative array of value->name pairs
	 */
	function getOptions(){
		return $this->_options;
	}

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
			$ret .= $this->getExtra()." />".$name.$this->delimeter."\n";
		}
		return $ret;
	}
}
?>