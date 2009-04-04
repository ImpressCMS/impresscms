<?php
/**
* Registration form
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license		LICENSE.txt
* @package	core
* @since		XOOPS
* @author		http://www.xoops.org The XOOPS Project
* @version		$Id$
*/
if (!defined("XOOPS_ROOT_PATH")) {
    die("ImpressCMS root path not defined");
}
include_once XOOPS_ROOT_PATH."/class/xoopslists.php";
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";


$email_tray = new XoopsFormElementTray(_US_EMAIL, "<br />");
$email_text = new XoopsFormText("", "email", 25, 60, $myts->htmlSpecialChars($email));
$email_option = new XoopsFormCheckBox("", "user_viewemail", $user_viewemail);
$email_option->addOption(1, _US_ALLOWVIEWEMAIL);
$email_tray->addElement($email_text, true);
$email_tray->addElement($email_option);

//$avatar_select = new XoopsFormSelect("", "user_avatar", $user_avatar);
//$avatar_array =& XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH."/images/avatar/");
//$avatar_select->addOptionArray($avatar_array);
//$a_dirlist =& XoopsLists::getDirListAsArray(XOOPS_ROOT_PATH."/images/avatar/");
//$a_dir_labels = array();
//$a_count = 0;
//$a_dir_link = "<a href=\"javascript:openWithSelfMain('".XOOPS_URL."/misc.php?action=showpopups&amp;type=avatars&amp;start=".$a_count."','avatars',600,400);\">XOOPS</a>";
//$a_count = $a_count + count($avatar_array);
//$a_dir_labels[] = new XoopsFormLabel("", $a_dir_link);
//foreach ($a_dirlist as $a_dir) {
//	if ( $a_dir == "users" ) {
//		continue;
//	}
//	$avatars_array =& XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH."/images/avatar/".$a_dir."/", $a_dir."/");
//	$avatar_select->addOptionArray($avatars_array);
//	$a_dir_link = "<a href=\"javascript:openWithSelfMain('".XOOPS_URL."/misc.php?action=showpopups&amp;type=avatars&amp;subdir=".$a_dir."&amp;start=".$a_count."','avatars',600,400);\">".$a_dir."</a>";
//	$a_dir_labels[] = new XoopsFormLabel("", $a_dir_link);
//	$a_count = $a_count + count($avatars_array);
//}
//$avatar_select->setExtra("onchange='showImgSelected(\"avatar\", \"user_avatar\", \"images/avatar\", \"\", \"".XOOPS_URL."\")'");
//$avatar_label = new XoopsFormLabel("", "<img src='images/avatar/blank.gif' name='avatar' id='avatar' alt='' />");
//$avatar_tray = new XoopsFormElementTray(_US_AVATAR, "&nbsp;");
//$avatar_tray->addElement($avatar_select);
//$avatar_tray->addElement($avatar_label);
//foreach ($a_dir_labels as $a_dir_label) {
//	$avatar_tray->addElement($a_dir_label);
//}

$reg_form = new XoopsThemeForm(_US_USERREG, "userinfo", "register.php", "post", true);
$uname_size = $xoopsConfigUser['maxuname'] < 75 ? $xoopsConfigUser['maxuname'] : 75;
$uname_size = $xoopsConfigUser['maxuname'] > 3 ? $xoopsConfigUser['maxuname'] : 3;
$reg_form->addElement(new XoopsFormText(_US_NICKNAME, "uname", $uname_size, $uname_size, $myts->htmlSpecialChars($uname)), true);
$login_name_size = $xoopsConfigUser['maxuname'] < 75 ? $xoopsConfigUser['maxuname'] : 75;
$reg_form->addElement(new XoopsFormText(_US_LOGIN_NAME, "login_name", $login_name_size, $login_name_size, $myts->htmlSpecialChars($login_name)), true);
$reg_form->addElement($email_tray);
//$reg_form->addElement(new XoopsFormPassword(_US_PASSWORD, "pass", 10, 72, $myts->htmlSpecialChars($pass)), true);
//$reg_form->addElement(new XoopsFormPassword(_US_VERIFYPASS, "vpass", 10, 72, $myts->htmlSpecialChars($vpass)), true);
if($xoopsConfigUser['pass_level']){
$xoTheme->addScript(ICMS_URL.'/libraries/jquery/jquery.js', array('type' => 'text/javascript'));
$xoTheme->addScript(ICMS_URL.'/libraries/jquery/password_strength_plugin.js', array('type' => 'text/javascript'));
$xoTheme->addScript('', array('type' => ''), '
                $(document).ready( function() {
                    $.fn.shortPass = "'._CORE_PASSLEVEL1.'";
                    $.fn.badPass = "'._CORE_PASSLEVEL2.'";
                    $.fn.goodPass = "'._CORE_PASSLEVEL3.'";
                    $.fn.strongPass = "'._CORE_PASSLEVEL4.'";
                    $.fn.samePassword = "Username and Password identical.";
                    $.fn.resultStyle = "";
				$(".password_adv").passStrength({
					shortPass: 		"top_shortPass",
					badPass:		"top_badPass",
					goodPass:		"top_goodPass",
					strongPass:		"top_strongPass",
					baseStyle:		"top_testresult",
					messageloc:		0

				});
			});
');
}
$reg_form->addElement(new XoopsFormPassword(_US_PASSWORD, "pass", 10, 255, $myts->htmlSpecialChars($pass), false, ($xoopsConfigUser['pass_level']?'password_adv':'')), true);
$reg_form->addElement(new XoopsFormPassword(_US_VERIFYPASS, "vpass", 10, 255, $myts->htmlSpecialChars($vpass)), true);
$reg_form->addElement(new XoopsFormText(_US_WEBSITE, "url", 25, 255, $myts->htmlSpecialChars($url)));
$tzselected = ($timezone_offset != "") ? $timezone_offset : $xoopsConfig['default_TZ'];
$reg_form->addElement(new XoopsFormSelectTimezone(_US_TIMEZONE, "timezone_offset", $tzselected));
//$reg_form->addElement($avatar_tray);
$reg_form->addElement(new XoopsFormRadioYN(_US_MAILOK, 'user_mailok', $user_mailok));

if ($xoopsConfigUser['reg_dispdsclmr'] != 0 && $xoopsConfigUser['reg_disclaimer'] != '') {
	$disc_tray = new XoopsFormElementTray(_US_DISCLAIMER, '<br />');
	$disclaimer_html = '<div id="disclaimer">'.nl2br($xoopsConfigUser['reg_disclaimer']).'</div>';
	$disc_text = new XoopsFormLabel('', $disclaimer_html, 'disclaimer');
	$disc_tray->addElement($disc_text);
	$agree_chk = new XoopsFormCheckBox('', 'agree_disc', $agree_disc);
	$agree_chk->addOption(1, _US_IAGREE);
	$eltname = $agree_chk->getName();
	$eltmsg = str_replace('"', '\"', stripslashes( sprintf( _FORM_ENTER, _US_IAGREE ) ) );
	$agree_chk->customValidationCode[] = "if ( myform.{$eltname}.checked == false ) { window.alert(\"{$eltmsg}\"); myform.{$eltname}.focus(); return false; }";
	$disc_tray->addElement($agree_chk, true);
	$reg_form->addElement($disc_tray);
}

$reg_form->addElement(new XoopsFormHidden("salt", $myts->htmlSpecialChars($salt)));
$reg_form->addElement(new XoopsFormHidden("enc_type", intval($enc_type)));
$reg_form->addElement(new XoopsFormHidden("actkey", $myts->htmlSpecialChars($actkey)));

if ($xoopsConfigUser['use_captcha'] == 1) {
	$reg_form->addElement(new IcmsFormCaptcha(_SECURITYIMAGE_GETCODE, "scode"), true);
	$reg_form->addElement(new XoopsFormHidden("op", "finish"));
} else {
	$reg_form->addElement(new XoopsFormHidden("op", "newuser"));
}

$reg_form->addElement(new XoopsFormButton("", "submit", _US_SUBMIT, "submit"));

?>