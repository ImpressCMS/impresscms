<?php
/**
 * Editor framework for XOOPS
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since		1.00
 * @version		$Id: textarea.php,v 1.1 2007/06/05 14:44:27 marcan Exp $
 * @package		xoopseditor
 */
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

require_once XOOPS_ROOT_PATH."/class/xoopsform/formtextarea.php";

/**
 * Pseudo class
 *
 * @author	    phppp (D.J.)
 * @copyright	copyright (c) 2005 XOOPS.org
 *
 */

class FormTextArea extends XoopsFormTextArea
{
	/**
	 * Constructor
	 *
     * @param	array   $configs  Editor Options
     * @param	binary 	$checkCompatible  true - return false on failure
	 */
	function FormTextArea($configs, $checkCompatible = false)
	{
		if(!empty($configs)) {
			foreach($configs as $key => $val){
				${$key} = $val;
				$this->$key = $val;
			}
		}
		$value = isset($value)? $value : "";
		$rows = isset($rows)? $rows : 5;
		$cols = isset($cols)? $cols : 50;
		$this->XoopsFormTextArea(@$caption, $name, $value, $rows, $cols);
	}
}
?>
