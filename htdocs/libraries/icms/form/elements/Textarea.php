<?php
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
/**
 * Creates a textarea form attribut
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)

 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 * @version		SVN: $Id: Textarea.php 12313 2013-09-15 21:14:35Z skenow $
 */
defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * A textarea
 *
 * @category	ICMS
 * @package     Form
 * @subpackage  Elements
 *
 * @author		Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class icms_form_elements_Textarea extends icms_form_Element {
	/**
	 * number of columns
	 * @var	int
	 */
	protected $_cols;

	/**
	 * number of rows
	 * @var	int
	 */
	protected $_rows;

	/**
	 * initial content
	 * @var	string
	 */
	protected $_value;

	/**
	 * Constuctor
	 *
	 * @param	string  $caption    caption
	 * @param	string  $name       name
	 * @param	string  $value      initial content
	 * @param	int     $rows       number of rows
	 * @param	int     $cols       number of columns
	 */
	public function __construct($caption, $name, $value = "", $rows = 5, $cols = 50) {
		$this->setCaption($caption);
		$this->setName($name);
		$this->_rows = (int) $rows;
		$this->_cols = (int) $cols;
		$this->setValue($value);
	}

	/**
	 * get number of rows
	 *
	 * @return	int
	 */
	public function getRows() {
		return $this->_rows;
	}

	/**
	 * Get number of columns
	 *
	 * @return	int
	 */
	public function getCols() {
		return $this->_cols;
	}

	/**
	 * Get initial content
	 *
	 * @param	bool    $encode To sanitize the text? Default value should be "true"; however we have to set "false" for backward compatibility
	 * @return	string
	 */
	public function getValue($encode = false) {
		return $encode ? htmlspecialchars($this->_value) : $this->_value;
	}

	/**
	 * Set initial content
	 *
	 * @param	$value	string
	 */
	public function setValue($value){
		$this->_value = $value;
	}

	/**
	 * prepare HTML for output
	 *
	 * @return string HTML
	 */
	public function render(){
		return "<textarea name='" . $this->getName()
			. "' id='" . $this->getName() . '_tarea'
			. "' rows='" . $this->getRows()
			. "' cols='" . $this->getCols()
			. "'" . $this->getExtra() . ">"
			. $this->getValue()
			. "</textarea>";
	}
}

