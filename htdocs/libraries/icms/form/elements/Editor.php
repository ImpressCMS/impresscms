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
 * Creates a form editor object
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 *
 * @author		modified by UnderDog <underdog@impresscms.org>
 * @version		$Id: Editor.php 12313 2013-09-15 21:14:35Z skenow $
 */

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

/**
 * XoopsEditor hanlder
 *
 * @since		XOOPS
 * @author	D.J.
 * @copyright	copyright (c) 2000-2005 XOOPS.org
 *
 * @todo		To be removed as this is not used anywhere in the core
 */
class icms_form_elements_Editor extends icms_form_elements_Textarea {
	var $editor;

	/**
	 * Constructor
	 *
	 * @param	string  $caption    Caption
	 * @param	string  $name       "name" attribute
	 * @param	string  $value      Initial text
	 * @param	array 	$configs     configures
	 * @param	bool  	$noHtml       use non-WYSIWYG eitor onfailure
	 * @param	string  $OnFailure editor to be used if current one failed
	 */
	function icms_form_elements_Editor($caption, $name, $editor_configs = null, $noHtml=false, $OnFailure = "")
	{
		parent::__construct($caption, $editor_configs["name"]);
		$editor_handler = icms_plugins_EditorHandler::getInstance();
		$this->editor =& $editor_handler->get($name, $editor_configs, $noHtml, $OnFailure);
	}

	/**
	 * Renders the editor
	 * @return	string  the constructed html string for the editor
	 */
	function render()
	{
		return $this->editor->render();
	}
}
