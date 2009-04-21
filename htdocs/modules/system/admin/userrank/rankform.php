<?php
/**
* Administration of user ranks, form file
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license	LICENSE.txt
* @package	Administration
* @since	XOOPS
* @author	http://www.xoops.org The XOOPS Project
* @author	modified by UnderDog <underdog@impresscms.org>
* @version	$Id$
*/

global $xoopsConfigUser;
include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
$rank_form = new XoopsThemeForm($rank['form_title'], 'rankform', 'admin.php', 'post', true);
$rank_form->setExtra('enctype="multipart/form-data"');
$rank_form->addElement(new XoopsFormText(_AM_RANKTITLE, 'rank_title', 50, 50, $rank['rank_title']), true);
$rank_form->addElement(new XoopsFormText(_AM_MINPOST, 'rank_min', 10, 10, $rank['rank_min']));
$rank_form->addElement(new XoopsFormText(_AM_MAXPOST, 'rank_max', 10, 10, $rank['rank_max']));
$rank_tray = new XoopsFormElementTray(_AM_IMAGE, '&nbsp;');
$rank_select = new XoopsFormFile('', 'rank_image', 5000000);
$rank_tray->addElement($rank_select);
if (trim($rank['rank_image']) != '' && file_exists(XOOPS_UPLOAD_PATH.'/'.$rank['rank_image'])) {
	$rank_label = new XoopsFormLabel('', '<img src="'.XOOPS_UPLOAD_URL.'/'.$rank['rank_image'].'" alt="" />');
	$rank_tray->addElement($rank_label);
	unset($rank_label);
}
$rank_label = new XoopsFormLabel('', '<br />' . sprintf(_AM_RANKW, icms_conv_nr2local($xoopsConfigUser['rank_width'])) . '<br />' . sprintf(_AM_RANKH, icms_conv_nr2local($xoopsConfigUser['rank_height'])) . '<br />' . sprintf(_AM_RANKMAX, icms_conv_nr2local($xoopsConfigUser['rank_maxsize'])) );
$rank_tray->addElement($rank_label);

$rank_form->addElement($rank_tray);
$tray = new XoopsFormElementTray(_AM_SPECIAL, '<br />');
$tray->addElement(new XoopsFormRadioYN('', 'rank_special', $rank['rank_special']));
$tray->addElement(new XoopsFormLabel('', _AM_SPECIALCAN));
$rank_form->addElement($tray);
$rank_form->addElement(new XoopsFormHidden('rank_id', $rank['rank_id']));
$rank_form->addElement(new XoopsFormHidden('op', $rank['op']));
$rank_form->addElement(new XoopsFormHidden('fct', 'userrank'));
$rank_form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
?>