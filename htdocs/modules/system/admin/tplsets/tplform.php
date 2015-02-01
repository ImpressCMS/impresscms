<?php
// $Id: tplform.php 12313 2013-09-15 21:14:35Z skenow $
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
 * Administration of template sets, form file
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @package		System
 * @subpackage	Template Sets
 * @version		SVN: $Id: tplform.php 12313 2013-09-15 21:14:35Z skenow $
 */
if ($tform['tpl_tplset'] != 'default') {
	$form = new icms_form_Theme(_MD_EDITTEMPLATE, 'template_form', 'admin.php', 'post', TRUE);
} else {
	$form = new icms_form_Theme(_MD_VIEWTEMPLATE, 'template_form', 'admin.php', 'post', TRUE);
}
$form->addElement(new icms_form_elements_Label(_MD_FILENAME, $tform['tpl_file']));
$form->addElement(new icms_form_elements_Label(_MD_FILEDESC, $tform['tpl_desc']));
$form->addElement(new icms_form_elements_Label(_MD_LASTMOD, formatTimestamp($tform['tpl_lastmodified'], 'l')));
$config = array(
	'name' => 'html',
	'value' => $tform['tpl_source'],
	'language' => _LANGCODE,
	'width' => '100%',
	'height' => '400px',
	'syntax' => 'html');
if ($tform['tpl_tplset'] == 'default') $config["is_editable"] = FALSE;
$tpl_src = icms_plugins_EditorHandler::getInstance('source')->get($icmsConfig['sourceeditor_default'], $config);
$tpl_src->setCaption(_MD_FILEHTML);
$form->addElement($tpl_src);
$form->addElement(new icms_form_elements_Hidden('id', $tform['tpl_id']));
$form->addElement(new icms_form_elements_Hidden('op', 'edittpl_go'));
$form->addElement(new icms_form_elements_Hidden('redirect', 'edittpl'));
$form->addElement(new icms_form_elements_Hidden('fct', 'tplsets'));
$form->addElement(new icms_form_elements_Hidden('moddir', $tform['tpl_module']));
if ($tform['tpl_tplset'] != 'default') {
	$button_tray = new icms_form_elements_Tray('');
	$button_tray->addElement(new icms_form_elements_Button('', 'previewtpl', _PREVIEW, 'submit'));
	$button_tray->addElement(new icms_form_elements_Button('', 'submittpl', _SUBMIT, 'submit'));
	$form->addElement($button_tray);
} else {
	$form->addElement(new icms_form_elements_Button('', 'previewtpl', _MD_VIEW, 'submit'));
}
