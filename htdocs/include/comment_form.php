<?php
// $Id: comment_form.php 12313 2013-09-15 21:14:35Z skenow $
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
 * The comment form extra include file
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @package	core
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version	$Id: comment_form.php 12313 2013-09-15 21:14:35Z skenow $
 */

if (!defined('ICMS_ROOT_PATH') || !is_object($icmsModule)) {
	exit();
}
$com_modid = $icmsModule->getVar('mid');
$cform = new icms_form_Theme(_CM_POSTCOMMENT, 'commentform', 'comment_post.php', 'post', TRUE);
if (isset($icmsModuleConfig['com_rule'])) {
	include_once ICMS_ROOT_PATH . '/include/comment_constants.php';
	switch ($icmsModuleConfig['com_rule']) {
		case XOOPS_COMMENT_APPROVEALL:
			$rule_text = _CM_COMAPPROVEALL;
			break;

		case XOOPS_COMMENT_APPROVEUSER:
			$rule_text = _CM_COMAPPROVEUSER;
			break;

		case XOOPS_COMMENT_APPROVEADMIN:
		default:
			$rule_text = _CM_COMAPPROVEADMIN;
			break;
	}
	$cform->addElement(new icms_form_elements_Label(_CM_COMRULES, $rule_text));
}

$cform->addElement(new icms_form_elements_Text(_CM_TITLE, 'com_title', 50, 255, $com_title), TRUE);
$icons_radio = new icms_form_elements_Radio(_MESSAGEICON, 'com_icon', $com_icon);
$subject_icons = icms_core_Filesystem::getFileList(ICMS_ROOT_PATH . "/images/subject/", '', array('gif', 'jpg', 'png'));
foreach ($subject_icons as $iconfile) {
	$icons_radio->addOption($iconfile, '<img src="' . ICMS_URL . '/images/subject/' . $iconfile . '" alt="" />');
}
$cform->addElement($icons_radio);
$cform->addElement(new icms_form_elements_Dhtmltextarea(_CM_MESSAGE, 'com_text', $com_text, 10, 50), TRUE);
$option_tray = new icms_form_elements_Tray(_OPTIONS, '<br />');

$button_tray = new icms_form_elements_Tray('' , '&nbsp;');

if (is_object(icms::$user)) {
	if ($icmsModuleConfig['com_anonpost'] == 1) {
		$noname = !empty($noname) ? 1 : 0;
		$noname_checkbox = new icms_form_elements_Checkbox('', 'noname', $noname);
		$noname_checkbox->addOption(1, _POSTANON);
		$option_tray->addElement($noname_checkbox);
	}
	if (FALSE != icms::$user->isAdmin($com_modid)) {
		// show status change box when editing (comment id is not empty)
		if (!empty($com_id)) {
			include_once ICMS_ROOT_PATH . '/include/comment_constants.php';
			$status_select = new icms_form_elements_Select(_CM_STATUS, 'com_status', $com_status);
			$status_select->addOptionArray(
				array(
					XOOPS_COMMENT_PENDING => _CM_PENDING,
					XOOPS_COMMENT_ACTIVE => _CM_ACTIVE,
					XOOPS_COMMENT_HIDDEN => _CM_HIDDEN,
				)
			);
			$cform->addElement($status_select);
			$button_tray->addElement(new icms_form_elements_Button('', 'com_dodelete', _DELETE, 'submit'));
		}
	}
    
    //$cform->addElement(new icms_form_elements_Hidden('dohtml', $dohtml));
    $html_checkbox = new icms_form_elements_Checkbox('', 'dohtml', $dohtml);
    $html_checkbox->addOption(1, _CM_DOHTML);
    $option_tray->addElement($html_checkbox);
}
$smiley_checkbox = new icms_form_elements_Checkbox('', 'dosmiley', $dosmiley);
$smiley_checkbox->addOption(1, _CM_DOSMILEY);
$option_tray->addElement($smiley_checkbox);
$xcode_checkbox = new icms_form_elements_Checkbox('', 'doxcode', $doxcode);
$xcode_checkbox->addOption(1, _CM_DOXCODE);
$option_tray->addElement($xcode_checkbox);
$br_checkbox = new icms_form_elements_Checkbox('', 'dobr', $dobr);
$br_checkbox->addOption(1, _CM_DOAUTOWRAP);
$option_tray->addElement($br_checkbox);

$cform->addElement($option_tray);
$cform->addElement(new icms_form_elements_Hidden('com_pid', (int) $com_pid));
$cform->addElement(new icms_form_elements_Hidden('com_rootid', (int) $com_rootid));
$cform->addElement(new icms_form_elements_Hidden('com_id', $com_id));
$cform->addElement(new icms_form_elements_Hidden('com_itemid', $com_itemid));
$cform->addElement(new icms_form_elements_Hidden('com_order', $com_order));
$cform->addElement(new icms_form_elements_Hidden('com_mode', $com_mode));

// add module specific extra params

if ('system' != $icmsModule->getVar('dirname')) {
	$comment_config = $icmsModule->getInfo('comments');
	if (isset($comment_config['extraParams']) && is_array($comment_config['extraParams'])) {
		foreach ($comment_config['extraParams'] as $extra_param) {
			// This routine is included from forms accessed via both GET and POST
			if (isset($_POST[$extra_param])) {
				$hidden_value = icms_core_DataFilter::stripSlashesGPC($_POST[$extra_param]);
			} elseif (isset($_GET[$extra_param])) {
				$hidden_value = icms_core_DataFilter::stripSlashesGPC($_GET[$extra_param]);
			} else {
				$hidden_value = '';
			}
			$cform->addElement(new icms_form_elements_Hidden($extra_param, icms_core_DataFilter::checkVar($hidden_value, 'str')));
		}
	}
}
// Captcha Hack
if ($icmsConfig['use_captchaf'] == TRUE) {
	$cform->addElement(new icms_form_elements_Captcha());
}
// Captcha Hack
$button_tray->addElement(new icms_form_elements_Button('', 'com_dopreview', _PREVIEW, 'submit'));
$button_tray->addElement(new icms_form_elements_Button('', 'com_dopost', _CM_POSTCOMMENT, 'submit'));
$cform->addElement($button_tray);
$cform->display();
