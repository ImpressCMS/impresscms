<?php
// $Id: searchform.php 12313 2013-09-15 21:14:35Z skenow $
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

/**
 * Search Form
 *
 * Shows form with options where to search in ImpressCMS
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @package		core
 * @version		SVN: $Id: searchform.php 12313 2013-09-15 21:14:35Z skenow $
 */

defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");

// create form
$search_form = new icms_form_Theme(_SR_SEARCH, "search", "search.php", 'get');

// create form elements
$search_form->addElement(new icms_form_elements_Text(_SR_KEYWORDS, "query", 30, 255, htmlspecialchars(stripslashes(implode(" ", $queries)), ENT_QUOTES)), true);
$type_select = new icms_form_elements_Select(_SR_TYPE, "andor", $andor);
$type_select->addOptionArray(array("AND"=>_SR_ALL, "OR"=>_SR_ANY, "exact"=>_SR_EXACT));
$search_form->addElement($type_select);

if (!empty($mids)) {
	$mods_checkbox = new icms_form_elements_Checkbox(_SR_SEARCHIN, "mids[]", $mids);
} else {
	$mods_checkbox = new icms_form_elements_Checkbox(_SR_SEARCHIN, "mids[]", $mid);
}

if (empty($modules)) {
	$criteria = new icms_db_criteria_Compo();
	$criteria->add(new icms_db_criteria_Item('hassearch', 1));
	$criteria->add(new icms_db_criteria_Item('isactive', 1));
	if (!empty($available_modules)) {
		$criteria->add(new icms_db_criteria_Item('mid', "(" . implode(',', $available_modules) . ")", 'IN'));
	}
	$module_handler = icms::handler('icms_module');
	$mods_checkbox->addOptionArray($module_handler->getList($criteria));
} else {
	unset($module);
	foreach (array_keys($modules) as $mid) $module_array[$mid] = $modules[$mid]->getVar('name');
	$mods_checkbox->addOptionArray($module_array);
}

$search_form->addElement($mods_checkbox);
if ($icmsConfigSearch['keyword_min'] > 0) {
	$search_form->addElement(new icms_form_elements_Label(_SR_SEARCHRULE, sprintf(_SR_KEYIGNORE, icms_conv_nr2local($icmsConfigSearch['keyword_min']))));
}

$search_form->addElement(new icms_form_elements_Hidden("action", "results"));
$search_form->addElement(new icms_form_elements_Hiddentoken('id'));
$search_form->addElement(new icms_form_elements_Button("", "submit", _SR_SEARCH, "submit"));
return $search_form->render();

