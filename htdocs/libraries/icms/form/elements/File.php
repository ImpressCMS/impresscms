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
 * Creates a form file field
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 * @version		$Id: File.php 12313 2013-09-15 21:14:35Z skenow $
 */
defined('ICMS_ROOT_PATH')or die("ImpressCMS root path not defined");

/**
 * Create a field for uploading a file
 *
 * @category	ICMS
 * @package     Form
 * @subpackage	Elements
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class icms_form_elements_File extends icms_form_Element {
	/**
	 * Maximum size for an uploaded file
	 * @var	int
	 */
	private $_maxFileSize;

	/**
	 * Constructor
	 *
	 * @param	string	$caption		Caption
	 * @param	string	$name			"name" attribute
	 * @param	int		$maxfilesize	Maximum size for an uploaded file
	 */
	public function __construct($caption, $name, $maxfilesize = '4096000') {
		$this->setCaption($caption);
		$this->setName($name);
		$this->_maxFileSize = (int) ($maxfilesize);
	}

	/**
	 * Get the maximum filesize
	 *
	 * @return	int
	 */
	public function getMaxFileSize() {
		return $this->_maxFileSize;
	}

	/**
	 * prepare HTML for output
	 *
	 * @return	string	HTML
	 */
	public function render() {
		$ele_name = $this->getName();
		$ret  = "<input type='hidden' name='MAX_FILE_SIZE' value='" . $this->getMaxFileSize() . "' />";
		$ret .= "<input type='file' name='" . $ele_name . "' id='" . $ele_name . "'" . $this->getExtra() . " />";
		$ret .= "<input type='hidden' name='xoops_upload_file[]' id='xoops_upload_file[]' value='" . $ele_name . "' />";
		return $ret;
	}
}

