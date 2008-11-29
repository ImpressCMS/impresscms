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

$modversion['name'] = _PROFILE_MI_NAME;
$modversion['version'] = 1.02;
$modversion['description'] = _PROFILE_MI_DESC;
$modversion['author'] = "Jan Pedersen";
$modversion['credits'] = "The XOOPS Project, Ackbarr for the general extensible profile idea";
$modversion['license'] = "GPL see XOOPS LICENSE";
$modversion['official'] = 0;
$modversion['image'] = "images/module_logo.gif";
$modversion['dirname'] = basename( dirname( __FILE__ ) );

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/admin.php";
$modversion['adminmenu'] = "admin/menu.php";

$modversion['onInstall'] = "include/install.php";

// Menu
$modversion['hasMain'] = 1;
global $xoopsUser;
if ($xoopsUser) {
    $modversion['sub'][1]['name'] = _PROFILE_MI_EDITACCOUNT;
    $modversion['sub'][1]['url'] = "edituser.php";
    $modversion['sub'][2]['name'] = _PROFILE_MI_PAGE_SEARCH;
    $modversion['sub'][2]['url'] = "search.php";
    $modversion['sub'][3]['name'] = _PROFILE_MI_CHANGEPASS;
    $modversion['sub'][3]['url'] = "changepass.php";
    if (isset($GLOBALS['xoopsModuleConfig']) && isset($GLOBALS['xoopsModuleConfig']['allow_chgmail']) && $GLOBALS['xoopsModuleConfig']['allow_chgmail'] == 1) {
        $modversion['sub'][4]['name'] = _PROFILE_MI_CHANGEMAIL;
        $modversion['sub'][4]['url'] = "changemail.php";
    }
}

$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$i =1;

$modversion['blocks'][$i]['file'] = "profile_newmembers.php";
$modversion['blocks'][$i]['name'] = _MI_SPROFILE_BLOCK_NEW_MEMBERS;
$modversion['blocks'][$i]['description']	= _MI_SPROFILE_BLOCK_NEW_MEMBERS_DSC;
$modversion['blocks'][$i]['show_func'] = "b_profile_newmembers_show";
$modversion['blocks'][$i]['edit_func'] = "b_profile_newmembers_edit";
$modversion['blocks'][$i]['options']	= "10|1";
$modversion['blocks'][$i]['template'] = 'profile_block_newusers.html';


// Tables created by sql file (without prefix!)
$modversion['tables'][1] = "profile_category";
$modversion['tables'][2] = "profile_profile";
$modversion['tables'][3] = "profile_field";
$modversion['tables'][4] = "profile_visibility";
$modversion['tables'][5] = "profile_regstep";
$modversion['tables'][6] = "profile_meta";

//update things
//$modversion['onUpdate'] = 'include/update.php';

// Config categories
$modversion['configcat'][1]['nameid'] = 'settings';
$modversion['configcat'][1]['name'] = '_PROFILE_MI_CAT_SETTINGS';
$modversion['configcat'][1]['description'] = '_PROFILE_MI_CAT_SETTINGS_DSC';

$modversion['configcat'][2]['nameid'] = 'user';
$modversion['configcat'][2]['name'] = '_PROFILE_MI_CAT_USER';
$modversion['configcat'][2]['description'] = '_PROFILE_MI_CAT_USER_DSC';

// Config items
$modversion['config'][1]['name'] = 'profile_search';
$modversion['config'][1]['title'] = '_PROFILE_MI_PROFILE_SEARCH';
$modversion['config'][1]['description'] = '_PROFILE_MI_PROFILE_SEARCH_DSC';
$modversion['config'][1]['formtype'] = 'yesno';
$modversion['config'][1]['valuetype'] = 'int';
$modversion['config'][1]['default'] = 1;
$modversion['config'][1]['category'] = 'settings';

$modversion['config'][2]['name'] = 'max_uname';
$modversion['config'][2]['title'] = '_PROFILE_MI_MAX_UNAME';
$modversion['config'][2]['description'] = '_PROFILE_MI_MAX_UNAME_DESC';
$modversion['config'][2]['formtype'] = 'textbox';
$modversion['config'][2]['valuetype'] = 'int';
$modversion['config'][2]['default'] = 20;
$modversion['config'][2]['category'] = 'user';

$modversion['config'][3]['name'] = 'min_uname';
$modversion['config'][3]['title'] = '_PROFILE_MI_MIN_UNAME';
$modversion['config'][3]['description'] = '_PROFILE_MI_MIN_UNAME_DESC';
$modversion['config'][3]['formtype'] = 'textbox';
$modversion['config'][3]['valuetype'] = 'int';
$modversion['config'][3]['default'] = 3;
$modversion['config'][3]['category'] = 'user';

$modversion['config'][4]['name'] = 'display_disclaimer';
$modversion['config'][4]['title'] = '_PROFILE_MI_DISPLAY_DISCLAIMER';
$modversion['config'][4]['description'] = '_PROFILE_MI_DISP_DISC_DESC';
$modversion['config'][4]['formtype'] = 'yesno';
$modversion['config'][4]['valuetype'] = 'int';
$modversion['config'][4]['default'] = 0;
$modversion['config'][4]['category'] = 'settings';

$modversion['config'][5]['name'] = 'disclaimer';
$modversion['config'][5]['title'] = '_PROFILE_MI_DISCLAIMER';
$modversion['config'][5]['description'] = '_PROFILE_MI_DISCLAIMER_DESC';
$modversion['config'][5]['formtype'] = 'textarea';
$modversion['config'][5]['valuetype'] = 'text';
$modversion['config'][5]['default'] = "";
$modversion['config'][5]['category'] = 'settings';

$modversion['config'][6]['name'] = 'bad_unames';
$modversion['config'][6]['title'] = '_PROFILE_MI_BAD_UNAMES';
$modversion['config'][6]['description'] = '_PROFILE_MI_BAD_UNAMES_DESC';
$modversion['config'][6]['formtype'] = 'textarea';
$modversion['config'][6]['valuetype'] = 'array';
$modversion['config'][6]['default'] = "webmaster|^xoops|^admin";
$modversion['config'][6]['category'] = 'user';

$modversion['config'][7]['name'] = 'bad_emails';
$modversion['config'][7]['title'] = '_PROFILE_MI_BAD_EMAILS';
$modversion['config'][7]['description'] = '_PROFILE_MI_BAD_EMAILS_DESC';
$modversion['config'][7]['formtype'] = 'textarea';
$modversion['config'][7]['valuetype'] = 'array';
$modversion['config'][7]['default'] = "xoops.org$";
$modversion['config'][7]['category'] = 'user';

$modversion['config'][8]['name'] = 'minpass';
$modversion['config'][8]['title'] = '_PROFILE_MI_MINPASS';
$modversion['config'][8]['description'] = '_PROFILE_MI_MINPASS_DESC';
$modversion['config'][8]['formtype'] = 'textbox';
$modversion['config'][8]['valuetype'] = 'int';
$modversion['config'][8]['default'] = 3;
$modversion['config'][8]['category'] = 'user';

$modversion['config'][9]['name'] = 'new_user_notify';
$modversion['config'][9]['title'] = '_PROFILE_MI_NEWUNOTIFY';
$modversion['config'][9]['description'] = '_PROFILE_MI_NEWUNOTIFY_DESC';
$modversion['config'][9]['formtype'] = 'yesno';
$modversion['config'][9]['valuetype'] = 'int';
$modversion['config'][9]['default'] = 1;
$modversion['config'][9]['category'] = 'settings';

$modversion['config'][10]['name'] = 'new_user_notify_group';
$modversion['config'][10]['title'] = '_PROFILE_MI_NOTIFYTO';
$modversion['config'][10]['description'] = '_PROFILE_MI_NOTIFYTO_DESC';
$modversion['config'][10]['formtype'] = 'group';
$modversion['config'][10]['valuetype'] = 'int';
$modversion['config'][10]['default'] = 1;
$modversion['config'][10]['category'] = 'settings';

$modversion['config'][11]['name'] = 'activation_type';
$modversion['config'][11]['title'] = '_PROFILE_MI_ACTVTYPE';
$modversion['config'][11]['description'] = '_PROFILE_MI_ACTVTYPE_DESC';
$modversion['config'][11]['formtype'] = 'select';
$modversion['config'][11]['valuetype'] = 'int';
$modversion['config'][11]['default'] = 0;
$modversion['config'][11]['options'] = array('_PROFILE_MI_USERACTV' => 0,  '_PROFILE_MI_AUTOACTV' => 1, '_PROFILE_MI_ADMINACTV' => 2);$modversion['config'][11]['category'] = 'settings';

$modversion['config'][12]['name'] = 'activation_group';
$modversion['config'][12]['title'] = '_PROFILE_MI_ACTVGROUP';
$modversion['config'][12]['description'] = '_PROFILE_MI_ACTVGROUP_DESC';
$modversion['config'][12]['formtype'] = 'group';
$modversion['config'][12]['valuetype'] = 'int';
$modversion['config'][12]['default'] = 1;
$modversion['config'][12]['category'] = 'settings';

$modversion['config'][13]['name'] = 'uname_test_level';
$modversion['config'][13]['title'] = '_PROFILE_MI_UNAMELVL';
$modversion['config'][13]['description'] = '_PROFILE_MI_UNAMELVL_DESC';
$modversion['config'][13]['formtype'] = 'select';
$modversion['config'][13]['valuetype'] = 'int';
$modversion['config'][13]['default'] = 0;
$modversion['config'][13]['options'] = array('_PROFILE_MI_STRICT' => 0, '_PROFILE_MI_MEDIUM' => 1, '_PROFILE_MI_LIGHT' => 2);
$modversion['config'][13]['category'] = 'user';

$modversion['config'][14]['name'] = 'allow_register';
$modversion['config'][14]['title'] = '_PROFILE_MI_ALLOWREG';
$modversion['config'][14]['description'] = '_PROFILE_MI_ALLOWREG_DESC';
$modversion['config'][14]['formtype'] = 'yesno';
$modversion['config'][14]['valuetype'] = 'int';
$modversion['config'][14]['default'] = 1;
$modversion['config'][14]['category'] = 'settings';

$modversion['config'][19]['name'] = 'self_delete';
$modversion['config'][19]['title'] = '_PROFILE_MI_SELFDELETE';
$modversion['config'][19]['description'] = '_PROFILE_MI_SELFDELETE_DESC';
$modversion['config'][19]['formtype'] = 'yesno';
$modversion['config'][19]['valuetype'] = 'int';
$modversion['config'][19]['default'] = 0;
$modversion['config'][19]['category'] = 'settings';

$modversion['config'][20]['name'] = 'show_empty';
$modversion['config'][20]['title'] = '_PROFILE_MI_SHOWEMPTY';
$modversion['config'][20]['description'] = '_PROFILE_MI_SHOWEMPTY_DESC';
$modversion['config'][20]['formtype'] = 'yesno';
$modversion['config'][20]['valuetype'] = 'int';
$modversion['config'][20]['default'] = 0;
$modversion['config'][20]['category'] = 'settings';

$modversion['config'][21]['name'] = 'allow_chgmail';
$modversion['config'][21]['title'] = '_PROFILE_MI_ALLOWCHGMAIL';
$modversion['config'][21]['description'] = '_PROFILE_MI_ALLOWCHGMAIL_DESC';
$modversion['config'][21]['formtype'] = 'yesno';
$modversion['config'][21]['valuetype'] = 'int';
$modversion['config'][21]['default'] = 0;
$modversion['config'][21]['category'] = 'settings';
$i = 22;
$modversion['config'][$i]['name'] = 'perpage';
$modversion['config'][$i]['title'] = '_MI_SPROFILE_PERPAGE';
$modversion['config'][$i]['description'] = '_MI_SPROFILE_PERPAGE_DSC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'all';
$modversion['config'][$i]['category'] = 'settings';
$modversion['config'][$i]['options'] = array(5  => '5',
										10  => '10',
										15  => '15',
                                   		25   => '25',
                                   		50  => '50',
                                   		100   => '100',
                                  		 _MI_SPROFILE_ALL => 'all');
//real name disp
$i++;
$modversion['config'][$i]['name'] = 'index_real_name';
$modversion['config'][$i]['title'] = '_PROFILE_MI_DISPNAME';
$modversion['config'][$i]['description'] = '_PROFILE_MI_DISPNAME_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'nick';
$modversion['config'][$i]['category'] = 'settings';
$modversion['config'][$i]['options'] = array(_PROFILE_MI_NICKNAME  => 'nick',
										_PROFILE_MI_REALNAME  => 'real',
										_PROFILE_MI_BOTH  => 'both');

//avatar disp
$i++;
$modversion['config'][$i]['name'] = 'index_avatar';
$modversion['config'][$i]['title'] = '_PROFILE_MI_AVATAR_INDEX';
$modversion['config'][$i]['description'] = '_PROFILE_MI_AVATAR_INDEX_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 1;
$modversion['config'][$i]['category'] = 'settings';

//avatar height
$i++;
$modversion['config'][$i]['name'] = 'index_avatar_height';
$modversion['config'][$i]['title'] = '_PROFILE_MI_AVATAR_HEIGHT';
$modversion['config'][$i]['description'] = '_PROFILE_MI_AVATAR_HEIGHT_DESC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;
$modversion['config'][$i]['category'] = 'settings';

//avatar width
$i++;
$modversion['config'][$i]['name'] = 'index_avatar_width';
$modversion['config'][$i]['title'] = '_PROFILE_MI_AVATAR_WIDTH';
$modversion['config'][$i]['description'] = '_PROFILE_MI_AVATAR_WIDTH_DESC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;
$modversion['config'][$i]['category'] = 'settings';

$member_handler = &xoops_gethandler('member');
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('groupid', 3, '!='));
$group_list = &$member_handler->getGroupList($criteria);

foreach ($group_list as $key=>$group) {
	$groups[$group] = $key;
}

$i++;
$modversion['config'][$i]['name'] = 'view_group_3';
$modversion['config'][$i]['title'] = '_PROFILE_MI_GROUP_VIEW_3';
$modversion['config'][$i]['description'] = '_PROFILE_MI_GROUP_VIEW_DSC';
$modversion['config'][$i]['formtype'] = 'select_multi';
$modversion['config'][$i]['valuetype'] = 'array';
$modversion['config'][$i]['options'] = $groups;
$modversion['config'][$i]['default'] = $groups;
$modversion['config'][$i]['category'] = 'other';

$i++;
$modversion['config'][$i]['name'] = 'view_group_2';
$modversion['config'][$i]['title'] = '_PROFILE_MI_GROUP_VIEW_2';
$modversion['config'][$i]['description'] = '_PROFILE_MI_GROUP_VIEW_DSC';
$modversion['config'][$i]['formtype'] = 'select_multi';
$modversion['config'][$i]['valuetype'] = 'array';
$modversion['config'][$i]['options'] = $groups;
$modversion['config'][$i]['default'] = $groups;
$modversion['config'][$i]['category'] = 'other';

foreach ($groups as $groupid) {
	if($groupid > 3){
		$i++;
		$modversion['config'][$i]['name'] = 'view_group_'.$groupid;
		$modversion['config'][$i]['title'] = '_PROFILE_MI_GROUP_VIEW_'.$groupid;
		$modversion['config'][$i]['description'] = '_PROFILE_MI_GROUP_VIEW_DSC';
		$modversion['config'][$i]['formtype'] = 'select_multi';
		$modversion['config'][$i]['valuetype'] = 'array';
		$modversion['config'][$i]['options'] = $groups;
		$modversion['config'][$i]['default'] = $groups;
		$modversion['config'][$i]['category'] = 'other';
	}
}
// Templates

$i = 0;

$i++;
$modversion['templates'][$i]['file'] = 'profile_header.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'profile_footer.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'profile_header.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'profile_footer.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'profile_admin_fieldlist.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'profile_userinfo.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'profile_admin_categorylist.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'profile_search.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'profile_results.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'profile_admin_visibility.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'profile_admin_steplist.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'profile_register.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'profile_changepass.html';
$modversion['templates'][$i]['description'] = '';
/*
 * hack by felix<inbox> for validusdc
 * adding report page
 */
$i++;
$modversion['templates'][$i]['file'] = 'profile_report.html';
$modversion['templates'][$i]['description'] = '';
/*
 * End of hack by felix<inbox> for validusdc
 * adding report page
 */
$i++;
$modversion['templates'][$i]['file'] = 'profile_userlist.html';
$modversion['templates'][$i]['description'] = '';
// About stuff
$modversion['status_version'] = "Release Candidate";
$modversion['developer_website_url'] = "http://smartfactory.ca";
$modversion['developer_website_name'] = "The SmartFactory";
$modversion['developer_email'] = "info@smartfactory.ca";
$modversion['status'] = "RC";
$modversion['date'] = "Unreleased";

$modversion['people']['developers'][] = "Mithrandir (Jan Keller Pedersen)";
$modversion['people']['developers'][] = "marcan (Marc-Andr Lanciault)";

$modversion['people']['testers'][] = "SmartFactory Team";

//$modversion['people']['translators'][] = "translator 1";

//$modversion['people']['documenters'][] = "documenter 1";

//$modversion['people']['other'][] = "other 1";

//$modversion['demo_site_url'] = "http://inboxfactory.net/smartmail";
//$modversion['demo_site_name'] = "SmartMail Sandbox";
$modversion['support_site_url'] = "http://smartfactory.ca/modules/newbb";
$modversion['support_site_name'] = "SmartFactory Support Forums";
//$modversion['submit_bug'] = "http://dev.xoops.org/modules/xfmod/tracker/?group_id=1352&atid=1562";
//$modversion['submit_feature'] = "http://dev.xoops.org/modules/xfmod/tracker/?group_id=1352&atid=1565";

$modversion['author_word'] = "";
$modversion['warning'] = "Do not create fields with the same name as those in the users table - i.e. 'name', 'email', 'uname' etc.

There is a 'deactivate' functionality and a 'disabled' setting relating to user level, but since XOOPS 2.0 does not allow for disabled accounts, users will still be able to reactivate the account by retrieving a new activation key.";
?>