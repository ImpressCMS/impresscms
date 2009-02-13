<?php
/**
 * Extended User Profile
 *
 *
 * @copyright       The ImpressCMS Project http://www.impresscms.org/
 * @license         LICENSE.txt
 * @license			GNU General Public License (GPL) http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @package         modules
 * @since           1.2
 * @author          Jan Pedersen
 * @author          The SmartFactory <www.smartfactory.ca>
 * @author	   		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version         $Id$
 */

function icms_module_install_profile($module) {
    // Create registration steps
    addStep('Main Information', '', 1, 0);
    addStep('Additional Information', '', 2, 0);
    addStep('Other Settings', '', 3, 1);

    // Create categories
    addCategory('Private', 'Private Information (available to you and site personnel)', 1);
    addCategory('Public', 'Public profile data', 2);
    addCategory('Subscriptions', 'Subscriptions to Newsletters and More', 3);
    addCategory('Settings', 'Site-wide Settings', 4);
    addCategory('Other', 'Other Settings', 5);
    addCategory('Feedback', 'Feedback for Site Personnel', 6);

    // Add user fields
    icms_loadLanguageFile('core', 'notification');
    icms_loadLanguageFile('core', 'countries');
    include_once ICMS_ROOT_PATH . '/include/notification_constants.php';
    include_once ICMS_ROOT_PATH . '/class/xoopslists.php';
    $umode_options = array('nest' => _NESTED,
    					   'flat' => _FLAT,
    					   'thread' => _THREADED
    );
    $uorder_options = array(0 => _OLDESTFIRST,
                            1 => _NEWESTFIRST);
    $notify_mode_options = array(XOOPS_NOTIFICATION_MODE_SENDALWAYS=>_NOT_MODE_SENDALWAYS,
                                 XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE=>_NOT_MODE_SENDONCE,
                                 XOOPS_NOTIFICATION_MODE_SENDONCETHENWAIT=>_NOT_MODE_SENDONCEPERLOGIN);
    $notify_method_options = array( XOOPS_NOTIFICATION_METHOD_DISABLE=>_NOT_METHOD_DISABLE,
                                    XOOPS_NOTIFICATION_METHOD_PM=>_NOT_METHOD_PM,
                                    XOOPS_NOTIFICATION_METHOD_EMAIL=>_NOT_METHOD_EMAIL);
			$country_list = array (
				""   => "-",
				"AD" => _COUNTRY_AD,
				"AE" => _COUNTRY_AE,
				"AF" => _COUNTRY_AF,
				"AG" => _COUNTRY_AG,
				"AI" => _COUNTRY_AI,
				"AL" => _COUNTRY_AL,
				"AM" => _COUNTRY_AM,
				"AN" => _COUNTRY_AN,
				"AO" => _COUNTRY_AO,
				"AQ" => _COUNTRY_AQ,
				"AR" => _COUNTRY_AR,
				"AS" => _COUNTRY_AS,
				"AT" => _COUNTRY_AT,
				"AU" => _COUNTRY_AU,
				"AW" => _COUNTRY_AW,
				"AX" => _COUNTRY_AX,
				"AZ" => _COUNTRY_AZ,
				"BA" => _COUNTRY_BA,
				"BB" => _COUNTRY_BB,
				"BD" => _COUNTRY_BD,
				"BE" => _COUNTRY_BE,
				"BF" => _COUNTRY_BF,
				"BG" => _COUNTRY_BG,
				"BH" => _COUNTRY_BH,
				"BI" => _COUNTRY_BI,
				"BJ" => _COUNTRY_BJ,
				"BL" => _COUNTRY_BL,
				"BM" => _COUNTRY_BM,
				"BN" => _COUNTRY_BN,
				"BO" => _COUNTRY_BO,
				"BR" => _COUNTRY_BR,
				"BS" => _COUNTRY_BS,
				"BT" => _COUNTRY_BT,
				"BV" => _COUNTRY_BV,
				"BW" => _COUNTRY_BW,
				"BY" => _COUNTRY_BY,
				"BZ" => _COUNTRY_BZ,
				"CA" => _COUNTRY_CA,
				"CC" => _COUNTRY_CC,
				"CD" => _COUNTRY_CD,
				"CF" => _COUNTRY_CF,
				"CG" => _COUNTRY_CG,
				"CH" => _COUNTRY_CH,
				"CI" => _COUNTRY_CI,
				"CK" => _COUNTRY_CK,
				"CL" => _COUNTRY_CL,
				"CM" => _COUNTRY_CM,
				"CN" => _COUNTRY_CN,
				"CO" => _COUNTRY_CO,
				"CR" => _COUNTRY_CR,
				"CS" => _COUNTRY_CS,	//  Not listed in ISO 3166
				"CU" => _COUNTRY_CU,
				"CV" => _COUNTRY_CV,
				"CX" => _COUNTRY_CX,
				"CY" => _COUNTRY_CY,
				"CZ" => _COUNTRY_CZ,
				"DE" => _COUNTRY_DE,
				"DJ" => _COUNTRY_DJ,
				"DK" => _COUNTRY_DK,
				"DM" => _COUNTRY_DM,
				"DO" => _COUNTRY_DO,
				"DZ" => _COUNTRY_DZ,
				"EC" => _COUNTRY_EC,
				"EE" => _COUNTRY_EE,
				"EG" => _COUNTRY_EG,
				"EH" => _COUNTRY_EH,
				"ER" => _COUNTRY_ER,
				"ES" => _COUNTRY_ES,
				"ET" => _COUNTRY_ET,
				"FI" => _COUNTRY_FI,
				"FJ" => _COUNTRY_FJ,
				"FK" => _COUNTRY_FK,
				"FM" => _COUNTRY_FM,
				"FO" => _COUNTRY_FO,
				"FR" => _COUNTRY_FR,
				"FX" => _COUNTRY_FX,	//  Not listed in ISO 3166
				"GA" => _COUNTRY_GA,
				"GB" => _COUNTRY_GB,
				"GD" => _COUNTRY_GD,
				"GE" => _COUNTRY_GE,
				"GF" => _COUNTRY_GF,
				"GG" => _COUNTRY_GG,
				"GH" => _COUNTRY_GH,
				"GI" => _COUNTRY_GI,
				"GL" => _COUNTRY_GL,
				"GM" => _COUNTRY_GM,
				"GN" => _COUNTRY_GN,
				"GP" => _COUNTRY_GP,
				"GQ" => _COUNTRY_GQ,
				"GR" => _COUNTRY_GR,
				"GS" => _COUNTRY_GS,
				"GT" => _COUNTRY_GT,
				"GU" => _COUNTRY_GU,
				"GW" => _COUNTRY_GW,
				"GY" => _COUNTRY_GY,
				"HK" => _COUNTRY_HK,
				"HM" => _COUNTRY_HM,
				"HN" => _COUNTRY_HN,
				"HR" => _COUNTRY_HR,
				"HT" => _COUNTRY_HT,
				"HU" => _COUNTRY_HU,
				"ID" => _COUNTRY_ID,
				"IE" => _COUNTRY_IE,
				"IL" => _COUNTRY_IL,
				"IM" => _COUNTRY_IM,
				"IN" => _COUNTRY_IN,
				"IO" => _COUNTRY_IO,
				"IQ" => _COUNTRY_IQ,
				"IR" => _COUNTRY_IR,
				"IS" => _COUNTRY_IS,
				"IT" => _COUNTRY_IT,
				"JM" => _COUNTRY_JM,
				"JO" => _COUNTRY_JO,
				"JP" => _COUNTRY_JP,
				"KE" => _COUNTRY_KE,
				"KG" => _COUNTRY_KG,
				"KH" => _COUNTRY_KH,
				"KI" => _COUNTRY_KI,
				"KM" => _COUNTRY_KM,
				"KN" => _COUNTRY_KN,
				"KP" => _COUNTRY_KP,
				"KR" => _COUNTRY_KR,
				"KW" => _COUNTRY_KW,
				"KY" => _COUNTRY_KY,
				"KZ" => _COUNTRY_KZ,
				"LA" => _COUNTRY_LA,
				"LB" => _COUNTRY_LB,
				"LC" => _COUNTRY_LC,
				"LI" => _COUNTRY_LI,
				"LK" => _COUNTRY_LK,
				"LR" => _COUNTRY_LR,
				"LS" => _COUNTRY_LS,
				"LT" => _COUNTRY_LT,
				"LU" => _COUNTRY_LU,
				"LV" => _COUNTRY_LV,
				"LY" => _COUNTRY_LY,
				"MA" => _COUNTRY_MA,
				"MC" => _COUNTRY_MC,
				"MD" => _COUNTRY_MD,
				"ME" => _COUNTRY_ME,
				"MF" => _COUNTRY_MF,
				"MG" => _COUNTRY_MG,
				"MH" => _COUNTRY_MH,
				"MK" => _COUNTRY_MK,
				"ML" => _COUNTRY_ML,
				"MM" => _COUNTRY_MM,
				"MN" => _COUNTRY_MN,
				"MO" => _COUNTRY_MO,
				"MP" => _COUNTRY_MP,
				"MQ" => _COUNTRY_MQ,
				"MR" => _COUNTRY_MR,
				"MS" => _COUNTRY_MS,
				"MT" => _COUNTRY_MT,
				"MU" => _COUNTRY_MU,
				"MV" => _COUNTRY_MV,
				"MW" => _COUNTRY_MW,
				"MX" => _COUNTRY_MX,
				"MY" => _COUNTRY_MY,
				"MZ" => _COUNTRY_MZ,
				"NA" => _COUNTRY_NA,
				"NC" => _COUNTRY_NC,
				"NE" => _COUNTRY_NE,
				"NF" => _COUNTRY_NF,
				"NG" => _COUNTRY_NG,
				"NI" => _COUNTRY_NI,
				"NL" => _COUNTRY_NL,
				"NO" => _COUNTRY_NO,
				"NP" => _COUNTRY_NP,
				"NR" => _COUNTRY_NR,
				"NT" => _COUNTRY_NT,	//  Not listed in ISO 3166
				"NU" => _COUNTRY_NU,
				"NZ" => _COUNTRY_NZ,
				"OM" => _COUNTRY_OM,
				"PA" => _COUNTRY_PA,
				"PE" => _COUNTRY_PE,
				"PF" => _COUNTRY_PF,
				"PG" => _COUNTRY_PG,
				"PH" => _COUNTRY_PH,
				"PK" => _COUNTRY_PK,
				"PL" => _COUNTRY_PL,
				"PM" => _COUNTRY_PM,
				"PN" => _COUNTRY_PN,
				"PR" => _COUNTRY_PR,
				"PS" => _COUNTRY_PS,
				"PT" => _COUNTRY_PT,
				"PW" => _COUNTRY_PW,
				"PY" => _COUNTRY_PY,
				"QA" => _COUNTRY_QA,
				"RE" => _COUNTRY_RE,
				"RO" => _COUNTRY_RO,
				"RS" => _COUNTRY_RS,
				"RU" => _COUNTRY_RU,
				"RW" => _COUNTRY_RW,
				"SA" => _COUNTRY_SA,
				"SB" => _COUNTRY_SB,
				"SC" => _COUNTRY_SC,
				"SD" => _COUNTRY_SD,
				"SE" => _COUNTRY_SE,
				"SG" => _COUNTRY_SG,
				"SH" => _COUNTRY_SH,
				"SI" => _COUNTRY_SI,
				"SJ" => _COUNTRY_SJ,
				"SK" => _COUNTRY_SK,
				"SL" => _COUNTRY_SL,
				"SM" => _COUNTRY_SM,
				"SN" => _COUNTRY_SN,
				"SO" => _COUNTRY_SO,
				"SR" => _COUNTRY_SR,
				"ST" => _COUNTRY_ST,
				"SU" => _COUNTRY_SU,	//  Not listed in ISO 3166
				"SV" => _COUNTRY_SV,
				"SY" => _COUNTRY_SY,
				"SZ" => _COUNTRY_SZ,
				"TC" => _COUNTRY_TC,
				"TD" => _COUNTRY_TD,
				"TF" => _COUNTRY_TF,
				"TG" => _COUNTRY_TG,
				"TH" => _COUNTRY_TH,
				"TJ" => _COUNTRY_TJ,
				"TK" => _COUNTRY_TK,
				"TL" => _COUNTRY_TL,
				"TM" => _COUNTRY_TM,
				"TN" => _COUNTRY_TN,
				"TO" => _COUNTRY_TO,
				"TP" => _COUNTRY_TP,	//  Not listed in ISO 3166
				"TR" => _COUNTRY_TR,
				"TT" => _COUNTRY_TT,
				"TV" => _COUNTRY_TV,
				"TW" => _COUNTRY_TW,
				"TZ" => _COUNTRY_TZ,
				"UA" => _COUNTRY_UA,
				"UG" => _COUNTRY_UG,
				"UK" => _COUNTRY_UK,	//  Not listed in ISO 3166
				"UM" => _COUNTRY_UM,
				"US" => _COUNTRY_US,
				"UY" => _COUNTRY_UY,
				"UZ" => _COUNTRY_UZ,
				"VA" => _COUNTRY_VA,
				"VC" => _COUNTRY_VC,
				"VE" => _COUNTRY_VE,
				"VG" => _COUNTRY_VG,
				"VI" => _COUNTRY_VI,
				"VN" => _COUNTRY_VN,
				"VU" => _COUNTRY_VU,
				"WF" => _COUNTRY_WF,
				"WS" => _COUNTRY_WS,
				"YE" => _COUNTRY_YE,
				"YT" => _COUNTRY_YT,
				"YU" => _COUNTRY_YU,	//  Not listed in ISO 3166
				"ZA" => _COUNTRY_ZA,
				"ZM" => _COUNTRY_ZM,
				"ZR" => _COUNTRY_ZR,	//  Not listed in ISO 3166
				"ZW" => _COUNTRY_ZW
			);
    addField('user_icq', _PROFILE_MI_ICQ_TITLE, _PROFILE_MI_ICQ_DESCRIPTION, 2, 'textbox', 1, 0, 30, 21, '', 1, 1, 1, 0, 'a:0:{}', 2);
    addField('user_aim', _PROFILE_MI_AIM_TITLE, _PROFILE_MI_AIM_DESCRIPTION, 2, 'textbox', 1, 0, 30, 23, '', 1, 1, 1, 0, 'a:0:{}', 2);
    addField('user_yim', _PROFILE_MI_YIM_TITLE, _PROFILE_MI_YIM_DESCRIPTION, 2, 'textbox', 1, 0, 30, 22, '', 1, 1, 1, 0, 'a:0:{}', 2);
    addField('user_msnm', _PROFILE_MI_MSN_TITLE, _PROFILE_MI_MSN_DESCRIPTION, 2, 'textbox', 1, 0, 30, 24, '', 1, 1, 1, 0, 'a:0:{}', 2);

    addField('name', 'Display Name', 'Your publicly displayed name', 2, 'textbox', 1, 1, 25, 1, '', 1, 1, 1, 0, 'a:0:{}', 2);
    addField('openid', 'Yoour OpenID', 'Your OPENID login data', 2, 'textbox', 1, 1, 25, 1, '', 1, 1, 1, 0, 'a:0:{}', 2);
    addField('user_viewoid', _PROFILE_MI_VIEWEOID_TITLE, 'Allow users to see my openid address', 4, 'yesno', 3, 1, 1, 2, '0', 1, 1, 1, 0, 'a:0:{}', 3);
    addField('user_from', _PROFILE_MI_FROM_TITLE, _PROFILE_MI_FROM_DESCRIPTION, 2, 'textbox', 1, 0, 255, 2, '', 1, 1, 1, 0, 'a:0:{}', 2);
    addField('timezone_offset', 'Timezone', '', 4, 'timezone', 1, 1, 0, 8, '-6', 1, 1, 1, 0, 'a:0:{}', 0);
    addField('user_occ', _PROFILE_MI_OCCUPATION_TITLE, _PROFILE_MI_OCCUPATION_DESCRIPTION, 2, 'textbox', 1, 0, 255, 4, '', 1, 1, 1, 0, 'a:0:{}', 2);
    addField('user_intrest', _PROFILE_MI_INTEREST_TITLE, _PROFILE_MI_INTEREST_DESCRIPTION, 2, 'textbox', 1, 0, 255, 3, '', 1, 1, 1, 0, 'a:0:{}', 2);
    addField('bio', _PROFILE_MI_BIO_TITLE, _PROFILE_MI_BIO_DESCRIPTION, 2, 'textarea', 2, 0, 255, 6, '', 1, 1, 1, 0, 'a:0:{}', 2);
    addField('user_regdate', 'Join Date', '', 2, 'datetime', 3, 0, 10, 34, '', 1, 0, 1, 0, 'a:0:{}', 0);

    addField('user_viewemail', _PROFILE_MI_VIEWEMAIL_TITLE, 'Allow users to see my e-mail address', 4, 'yesno', 3, 1, 1, 2, '0', 1, 1, 1, 0, 'a:0:{}', 3);
    addField('attachsig', 'Attach Signature', 'Include signature in your submissions?', 4, 'yesno', 3, 1, 1, 1, '1', 1, 1, 1, 0, 'a:0:{}', 3);
    addField('user_mailok', 'Site Notifications', 'May we periodically send you information about your account and this site?', 3, 'yesno', 3, 1, 1, 1, '1', 1, 1, 1, 0, 'a:0:{}', 3);
    addField('theme', 'Theme', '', 4, 'theme', 1, 0, 0, 7, '', 1, 1, 0, 0, 'a:0:{}', 0);
    addField('umode', 'Comments Display', '', 4, 'select', 3, 1, 0, 5, 'nest', 1, 1, 1, 0, $umode_options, 0);
    addField('uorder', 'Comments Sorting', '', 4, 'select', 3, 1, 0, 6, 'XOOPS_COMMENT_OLD1ST', 1, 1, 1, 0, $uorder_options, 0);
    addField('notify_mode', 'Notification Frequency', '', 4, 'select', 3, 1, 0, 4, '0', 1, 1, 0, 0, $notify_mode_options, 0);
    addField('notify_method', 'Notification Method', '', 4, 'select', 3, 1, 0, 3, '2', 1, 1, 0, 0, $notify_method_options, 0);

    addField('url', _PROFILE_MI_URL_TITLE, _PROFILE_MI_URL_DESCRIPTION, 2, 'textbox', 1, 0, 255, 5, '', 1, 1, 1, 0, 'a:0:{}', 2);
    addField('posts', 'Posts', '', 2, 'textbox', 3, 0, 255, 31, '', 1, 1, 1, 0, 'a:0:{}', 0);
    addField('rank', 'Rank', '', 2, 'rank', 3, 0, 0, 32, '', 1, 1, 1, 0, 'a:0:{}', 0);
    addField('last_login', 'Last login', '', 2, 'datetime', 3, 0, 10, 33, '', 1, 0, 1, 0, 'a:0:{}', 0);
    addField('user_sig', _PROFILE_MI_SIG_TITLE, _PROFILE_MI_SIG_DESCRIPTION, 2, 'textarea', 2, 0, 255, 9, '', 1, 1, 1, 0, 'a:0:{}', 2);

    addField('first_name', 'First Name', '', 1, 'textbox', 1, 1, 20, 1, '', 1, 1, 1, 1, 'b:0;', 1);
    addField('middle_name', 'Middle Name', 'Middle Name or Initial', 1, 'textbox', 1, 0, 30, 2, '', 1, 1, 1, 1, 'b:0;', 1);
    addField('last_name', 'Last Name', '', 1, 'textbox', 1, 1, 50, 3, '', 1, 1, 1, 1, 'b:0;', 1);
    addField('company', 'Company', 'Company Name', 1, 'textbox', 1, 0, 255, 9, '', 1, 1, 1, 1, 'b:0;', 1);
    addField('address', 'Street Address', '', 1, 'textarea', 2, 0, 255, 10, '', 1, 1, 1, 1, 'b:0;', 1);
    addField('city', 'City', '', 1, 'textbox', 1, 0, 50, 11, '', 1, 1, 1, 1, 'b:0;', 1);
    addField('state', 'State/Province', '', 1, 'select', 1, 0, 255, 12, '', 1, 1, 1, 1, '', 1);
    addField('zip', 'Zip/Postal Code', '', 1, 'textbox', 1, 0, 10, 13, '', 1, 1, 1, 1, 'b:0;', 1);
    addField('country', 'Country', '', 1, 'select', 1, 1, 0, 14, '', 1, 1, 1, 1, $country_list, 1);
    addField('phone', 'Phone - Main', 'Primary Phone Number', 1, 'textbox', 1, 0, 25, 20, '', 1, 1, 1, 1, 'b:0;', 1);
    addField('phone2', 'Phone - Secondary', 'Secondary Phone', 1, 'textbox', 1, 0, 25, 21, '', 1, 1, 1, 1, 'b:0;', 1);
    addField('birth_date', 'Date of Birth', '', 1, 'date', 3, 0, 10, 7, '', 1, 1, 1, 1, 'b:0;', 1);
    addField('newsletter', 'Newsletter', 'Subscribe to our Newsletter?', 3, 'yesno', 3, 1, 1, 2, '1', 1, 1, 1, 1, 'b:0;', 3);
    addField('newsletter_partners', 'Partner News', 'May we send you site-related information from our partners?', 3, 'yesno', 3, 1, 1, 3, '1', 1, 1, 1, 1, 'b:0;', 3);
    addField('email_format', 'Email Format', 'Desired E-mail Format (if available)', 3, 'select', 1, 1, 255, 10, 'HTML', 1, 1, 1, 1, array('HTML' => 'HTML', 'TEXT' => 'Text'), 0);

    // Add visbility permissions
    addVisibility(1, 1, 0);
    addVisibility(1, 2, 0);
    addVisibility(2, 1, 0);
    addVisibility(2, 2, 0);
    addVisibility(3, 1, 0);
    addVisibility(3, 2, 0);
    addVisibility(4, 1, 0);
    addVisibility(4, 2, 0);
    addVisibility(5, 1, 0);
    addVisibility(5, 2, 0);
    addVisibility(6, 1, 0);
    addVisibility(6, 2, 0);
    addVisibility(7, 1, 0);
    addVisibility(8, 1, 0);
    addVisibility(8, 2, 0);
    addVisibility(9, 1, 0);
    addVisibility(9, 2, 0);
    addVisibility(10, 1, 0);
    addVisibility(10, 2, 0);
    addVisibility(11, 1, 0);
    addVisibility(11, 2, 0);
    addVisibility(12, 1, 0);
    addVisibility(13, 1, 0);
    addVisibility(14, 1, 0);
    addVisibility(15, 1, 0);
    addVisibility(16, 1, 0);
    addVisibility(17, 1, 0);
    addVisibility(18, 1, 0);
    addVisibility(19, 1, 0);
    addVisibility(20, 1, 0);
    addVisibility(20, 2, 0);
    addVisibility(21, 1, 0);
    addVisibility(21, 2, 0);
    addVisibility(22, 1, 0);
    addVisibility(22, 2, 0);
    addVisibility(23, 1, 0);
    addVisibility(23, 2, 0);
    addVisibility(24, 1, 0);
    addVisibility(24, 2, 0);
    addVisibility(25, 1, 0);
    addVisibility(26, 1, 0);
    addVisibility(27, 1, 0);
    addVisibility(28, 1, 0);
    addVisibility(29, 1, 0);
    addVisibility(30, 1, 0);
    addVisibility(31, 1, 0);
    addVisibility(32, 1, 0);
    addVisibility(33, 1, 0);
    addVisibility(34, 1, 0);
    addVisibility(35, 1, 0);
    addVisibility(36, 1, 0);
    addVisibility(37, 1, 0);
    addVisibility(38, 1, 0);
    addVisibility(39, 1, 0);

//    addProfileFields(); //MPB - Commented Out as caused error

    return true;
}

function addField($name, $title, $description, $category, $type, $valuetype, $required=0, $length, $weight, $default='', $notnull=1, $canedit, $show=1, $config=0, $options=array(), $step_id) {
    global $xoopsDB;
        $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("profile_field")." VALUES (0, ".$category.", '".$type."', ".$valuetype.", '".$name."', '".$title."', '".$description."', ".$required.", ".$length.", ".$weight.", '".$default."', ".$notnull.", ".$canedit.", ".$show.", ".$config.", '".addslashes(serialize($options))."', 0, ".$step_id.")");
}

function addCategory($name, $desc='', $weight) {
    global $xoopsDB;
    $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("profile_category")." VALUES (0, '".$name."', '".$desc."', ".$weight.")");
}

function addStep($name, $desc, $order, $save) {
    global $xoopsDB;
    $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("profile_regstep")." VALUES (0, '".$name."', '".$desc."', ".$order.", ".$save.")");
}

function addVisibility($fieldid, $user_group = 1, $profile_group = 0) {
    global $xoopsDB;
    $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("profile_visibility")." VALUES (".$fieldid.", ".$user_group.", ".$profile_group.")");
}


// MPB: CVS Function not ready for primetime
function addProfileFields() {
    global $xoopsModule;
    $profilefield_handler = icms_getmodulehandler( 'field', basename(  dirname(  dirname( __FILE__ ) ) ), 'profile' );
    $obj =& $profilefield_handler->create();
    $obj->setVar('field_name', 'newemail');
    $obj->setVar('field_moduleid', $xoopsModule->getVar('mid'));
    $obj->setVar('field_show', 0);
    $obj->setVar('field_edit', 0);
    $obj->setVar('field_config', 0);

    $obj->setVar('field_title', _PROFILE_MI_NEWEMAIL_TITLE);
    $obj->setVar('field_description', _PROFILE_MI_NEWEMAIL_DESCRIPTION);
    $obj->setVar('field_weight', 99);
    $obj->setVar('catid', 3);
    return $profilefield_handler->insert($obj);
}
?>