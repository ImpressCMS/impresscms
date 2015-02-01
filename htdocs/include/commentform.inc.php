<?php
// $Id: commentform.inc.php 12313 2013-09-15 21:14:35Z skenow $
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
 * The commentform include file
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @package		Administration
 * @subpackage	Comments
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version		SVN: $Id: commentform.inc.php 12313 2013-09-15 21:14:35Z skenow $
 */

defined("ICMS_ROOT_PATH") || die("ImpressCMS root path not defined");

$cform = new icms_form_Theme(_CM_POSTCOMMENT, "commentform", "postcomment.php", "post", true);
if (!preg_match("/^re:/i", $subject)) {
	$subject = "Re: " . icms_core_DataFilter::icms_substr($subject,0,56);
}
$cform->addElement(new icms_form_elements_Text(_CM_TITLE, 'subject', 50, 255, $subject), true);
$icons_radio = new icms_form_elements_Radio(_MESSAGEICON, 'icon', $icon);
$subject_icons = icms_core_Filesystem::getFileList(ICMS_ROOT_PATH . "/images/subject/", '', array('gif', 'jpg', 'png'));
foreach ($subject_icons as $iconfile) {
	$icons_radio->addOption($iconfile, '<img src="' . ICMS_IMAGES_URL . '/subject/' . $iconfile . '" alt="" />');
}
$cform->addElement($icons_radio);
$cform->addElement(new icms_form_elements_Dhtmltextarea(_CM_MESSAGE, 'message', $message, 10, 50), true);
$option_tray = new icms_form_elements_Tray(_OPTIONS,'<br />');
if (icms::$user) {
	if ($icmsConfig['anonpost'] == true) {
		$noname_checkbox = new icms_form_elements_Checkbox('', 'noname', $noname);
		$noname_checkbox->addOption(1, _POSTANON);
		$option_tray->addElement($noname_checkbox);
	}
//	if (icms::$user->isAdmin($icmsModule->getVar('mid'))) {
		$nohtml_checkbox = new icms_form_elements_Checkbox('', 'nohtml', $nohtml);
		$nohtml_checkbox->addOption(1, _DISABLEHTML);
		$option_tray->addElement($nohtml_checkbox);
//	}
}
$smiley_checkbox = new icms_form_elements_Checkbox('', 'nosmiley', $nosmiley);
$smiley_checkbox->addOption(1, _DISABLESMILEY);
$option_tray->addElement($smiley_checkbox);

$cform->addElement($option_tray);
$cform->addElement(new icms_form_elements_Hidden('pid', (int) $pid));
$cform->addElement(new icms_form_elements_Hidden('comment_id', (int) $comment_id));
$cform->addElement(new icms_form_elements_Hidden('item_id', (int) $item_id));
$cform->addElement(new icms_form_elements_Hidden('order', (int) $order));
$button_tray = new icms_form_elements_Tray('' ,'&nbsp;');
$button_tray->addElement(new icms_form_elements_Button('', 'preview', _PREVIEW, 'submit'));
$button_tray->addElement(new icms_form_elements_Button('', 'post', _CM_POSTCOMMENT, 'submit'));
$cform->addElement($button_tray);
$cform->display();
