<?php
/**
 * Configuration settings for local authentication store
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @package     ICMS/Authentication/Stores
 */

/*
	// Data for Config Category 2 (User Preferences)
	$c=2; // sets config category id
	$p=0; // reset position increment to 0 for new category id
*	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'allow_register', '_MD_AM_ALLOWREG', 1, '_MD_AM_ALLOWREGDSC', 'yesno', 'int', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'minpass', '_MD_AM_MINPASS', '5', '_MD_AM_MINPASSDSC', 'textbox', 'int', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'pass_level', '_MD_AM_PASSLEVEL', '40', '_MD_AM_PASSLEVEL_DESC', 'select', 'int', " . $p++ . ")");
	// Insert data for Config Options in selection field. (must be placed before //$i++)
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_PASSLEVEL1', '20', $i)");
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_PASSLEVEL2', '40', $i)");
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_PASSLEVEL3', '60', $i)");
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_PASSLEVEL4', '80', $i)");
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_PASSLEVEL5', '95', $i)");
	// ----------
	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'minuname', '_MD_AM_MINUNAME', '3', '_MD_AM_MINUNAMEDSC', 'textbox', 'int', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'maxuname', '_MD_AM_MAXUNAME', '20', '_MD_AM_MAXUNAMEDSC', 'textbox', 'int', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'delusers', '_MD_AM_DELUSRES', '30', '_MD_AM_DELUSRESDSC', 'textbox', 'int', " . $p++ . ")");
*	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'use_captcha', '_MD_AM_USECAPTCHA', 1, '_MD_AM_USECAPTCHADSC', 'yesno', 'int', " . $p++ . ")");
*	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'welcome_msg', '_MD_AM_WELCOMEMSG', '0', '_MD_AM_WELCOMEMSGDSC', 'yesno', 'int', " . $p++ . ")");
*	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'welcome_msg_content', '_MD_AM_WELCOMEMSG_CONTENT', '".addslashes(_WELCOME_MSG_CONTENT)."', '_MD_AM_WELCOMEMSG_CONTENTDSC', 'textsarea', 'text', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'allow_chgmail', '_MD_AM_ALLWCHGMAIL', '0', '_MD_AM_ALLWCHGMAILDSC', 'yesno', 'int', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'allow_chguname', '_MD_AM_ALLWCHGUNAME', '0', '_MD_AM_ALLWCHGUNAMEDSC', 'yesno', 'int', " . $p++ . ")");
*	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'allwshow_sig', '_MD_AM_ALLWSHOWSIG', '1', '_MD_AM_ALLWSHOWSIGDSC', 'yesno', 'int', " . $p++ . ")");
*	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'allow_htsig', '_MD_AM_ALLWHTSIG', '1', '_MD_AM_ALLWHTSIGDSC', 'yesno', 'int', " . $p++ . ")");
*	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'sig_max_length', '_MD_AM_SIGMAXLENGTH', '255', '_MD_AM_SIGMAXLENGTHDSC', 'textbox', 'int', " . $p++ . ")");
*	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'new_user_notify', '_MD_AM_NEWUNOTIFY', '1', '_MD_AM_NEWUNOTIFYDSC', 'yesno', 'int', " . $p++ . ")");
*	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'new_user_notify_group', '_MD_AM_NOTIFYTO', ".$gruops['XOOPS_GROUP_ADMIN'].", '_MD_AM_NOTIFYTODSC', 'group', 'int', " . $p++ . ")");
*	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'activation_type', '_MD_AM_ACTVTYPE', '0', '_MD_AM_ACTVTYPEDSC', 'select', 'int', " . $p++ . ")");
*	// Insert data for Config Options in selection field. (must be placed before //$i++)
*		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_USERACTV', '0', $i)");
*		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_AUTOACTV', '1', $i)");
*		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_ADMINACTV', '2', $i)");
*		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_REGINVITE', '3', $i)");
*	// ----------
*	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'activation_group', '_MD_AM_ACTVGROUP', ".$gruops['XOOPS_GROUP_ADMIN'].", '_MD_AM_ACTVGROUPDSC', 'group', 'int', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'uname_test_level', '_MD_AM_UNAMELVL', '0', '_MD_AM_UNAMELVLDSC', 'select', 'int', " . $p++ . ")");
	// Insert data for Config Options in selection field. (must be placed before //$i++)
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_STRICT', '0', $i)");
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_MEDIUM', '1', $i)");
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_LIGHT', '2', $i)");
	// ----------
*	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'avatar_allow_upload', '_MD_AM_AVATARALLOW', '0', '_MD_AM_AVATARALWDSC', 'yesno', 'int', " . $p++ . ")");
*	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'avatar_allow_gravatar', '_MD_AM_GRAVATARALLOW', '1', '_MD_AM_GRAVATARALWDSC', 'yesno', 'int', " . $p++ . ")");
*	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'avatar_minposts', '_MD_AM_AVATARMP', '0', '_MD_AM_AVATARMPDSC', 'textbox', 'int', " . $p++ . ")");
*	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'avatar_width', '_MD_AM_AVATARW', '80', '_MD_AM_AVATARWDSC', 'textbox', 'int', " . $p++ . ")");
*	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'avatar_height', '_MD_AM_AVATARH', '80', '_MD_AM_AVATARHDSC', 'textbox', 'int', " . $p++ . ")");
*	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'avatar_maxsize', '_MD_AM_AVATARMAX', '35000', '_MD_AM_AVATARMAXDSC', 'textbox', 'int', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'self_delete', '_MD_AM_SELFDELETE', '0', '_MD_AM_SELFDELETEDSC', 'yesno', 'int', " . $p++ . ")");
*	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'rank_width', '_MD_AM_RANKW', '120', '_MD_AM_RANKWDSC', 'textbox', 'int', " . $p++ . ")");
*	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'rank_height', '_MD_AM_RANKH', '120', '_MD_AM_RANKHDSC', 'textbox', 'int', " . $p++ . ")");
*	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'rank_maxsize', '_MD_AM_RANKMAX', '35000', '_MD_AM_RANKMAXDSC', 'textbox', 'int', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'bad_unames', '_MD_AM_BADUNAMES', '".addslashes(serialize(array('webmaster', '^impresscms', '^admin')))."', '_MD_AM_BADUNAMESDSC', 'textsarea', 'array', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'bad_emails', '_MD_AM_BADEMAILS', '".addslashes(serialize(array('impresscms.org$')))."', '_MD_AM_BADEMAILSDSC', 'textsarea', 'array', " . $p++ . ")");
*	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'remember_me', '_MD_AM_REMEMBERME', '0', '_MD_AM_REMEMBERMEDSC', 'yesno', 'int', " . $p++ . ")");
*	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'reg_dispdsclmr', '_MD_AM_DSPDSCLMR', 1, '_MD_AM_DSPDSCLMRDSC', 'yesno', 'int', " . $p++ . ")");
*	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'reg_disclaimer', '_MD_AM_REGDSCLMR', '".addslashes(_INSTALL_DISCLMR)."', '_MD_AM_REGDSCLMRDSC', 'textsarea', 'text', " . $p++ . ")");
*	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'priv_dpolicy', '_MD_AM_PRIVDPOLICY', 0, '_MD_AM_PRIVDPOLICYDSC', 'yesno', 'int', " . $p++ . ")");
*	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'priv_policy', '_MD_AM_PRIVPOLICY', '".addslashes(_INSTALL_PRIVPOLICY)."', '_MD_AM_PRIVPOLICYDSC', 'textsarea', 'text', " . $p++ . ")");
*	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'allow_annon_view_prof', '_MD_AM_ALLOW_ANONYMOUS_VIEW_PROFILE', '0', '_MD_AM_ALLOW_ANONYMOUS_VIEW_PROFILE_DESC', 'yesno', 'int', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ", 0, $c, 'enc_type', '_MD_AM_ENC_TYPE', '1', '_MD_AM_ENC_TYPEDSC', 'select', 'int', " . $p++ . ")");
	// Insert data for Config Options in selection field. (must be placed before //$i++)
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_ENC_MD5', '0', $i)");
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_ENC_SHA256', '1', $i)");
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_ENC_SHA384', '2', $i)");
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_ENC_SHA512', '3', $i)");
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_ENC_RIPEMD128', '4', $i)");
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_ENC_RIPEMD160', '5', $i)");
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_ENC_WHIRLPOOL', '6', $i)");
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_ENC_HAVAL1284', '7', $i)");
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_ENC_HAVAL1604', '8', $i)");
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_ENC_HAVAL1924', '9', $i)");
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_ENC_HAVAL2244', '10', $i)");
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_ENC_HAVAL2564', '11', $i)");
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_ENC_HAVAL1285', '12', $i)");
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_ENC_HAVAL1605', '13', $i)");
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_ENC_HAVAL1925', '14', $i)");
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_ENC_HAVAL2245', '15', $i)");
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_ENC_HAVAL2565', '16', $i)");
 */