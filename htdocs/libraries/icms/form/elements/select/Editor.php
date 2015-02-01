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
 * Creates a form attribute which is able to select an editor
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 *
 * @category	ICMS
 * @package		Forms
 * @subpackage	Elements
 * @version		SVN: $Id: formselecteditor.php 19892 2010-07-27 00:12:10Z skenow $
 */
/**
 * A select box with available editors
 *
 * @category	ICMS
 * @package     Form
 * @subpackage  Elements
 *
 * @author	    phppp (D.J.)
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class icms_form_elements_select_Editor extends icms_form_elements_Tray {
	/**
	 * Constructor
	 *
	 * @param	object	$form	the form calling the editor selection
	 * @param	string	$name	editor name
	 * @param	string	$value	Pre-selected text value
	 * @param	bool	$noHtml  dohtml disabled
	 */
	public function __construct(&$form, $name = "editor", $value = NULL, $noHtml = FALSE) {
		global $icmsConfig;

		if (empty($value)){
			$value = $icmsConfig['editor_default'];
		}

		parent::__construct(_SELECT);
		$edtlist = icms_plugins_EditorHandler::getListByType();
		$option_select = new icms_form_elements_Select("", $name, $value);
		$querys = preg_replace('/editor=(.*?)&/','',$_SERVER['QUERY_STRING']);
		$extra = 'onchange="if(this.options[this.selectedIndex].value.length > 0 ){
				window.location = \'?editor=\'+this.options[this.selectedIndex].value+\'&'.$querys.'\';
			}"';
		$option_select->setExtra($extra);
		$option_select->addOptionArray($edtlist);

		$this->addElement($option_select);
	}
}
