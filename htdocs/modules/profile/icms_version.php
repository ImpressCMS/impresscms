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
$modversion['version'] = 1.20;
$modversion['description'] = _PROFILE_MI_DESC;
$modversion['author'] = "Jan Pedersen";
$modversion['credits'] = "The XOOPS Project, Ackbarr for the general extensible profile idea";
$modversion['license'] = "GNU General Public License (GPL)";
$modversion['official'] = 0;
$modversion['image'] = "images/module_logo.gif";
$modversion['dirname'] = basename( dirname( __FILE__ ) );
$modversion['modname'] = 'profile';

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
//$modversion['tables'][6] = "profile_meta";

//update things
//$modversion['onUpdate'] = 'include/update.php';

// Config categories
$modversion['configcat'][1]['nameid'] = 'settings';
$modversion['configcat'][1]['name'] = '_PROFILE_MI_CAT_SETTINGS';
$modversion['configcat'][1]['description'] = '_PROFILE_MI_CAT_SETTINGS_DSC';

$modversion['configcat'][2]['nameid'] = 'user';
$modversion['configcat'][2]['name'] = '_PROFILE_MI_CAT_USER';
$modversion['configcat'][2]['description'] = '_PROFILE_MI_CAT_USER_DSC';

$i = 1;
// Config items
$modversion['config'][$i]['name'] = 'profile_search';
$modversion['config'][$i]['title'] = '_PROFILE_MI_PROFILE_SEARCH';
$modversion['config'][$i]['description'] = '_PROFILE_MI_PROFILE_SEARCH_DSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 1;
$modversion['config'][$i]['category'] = 'settings';

$i++;
$modversion['config'][$i]['name'] = 'show_empty';
$modversion['config'][$i]['title'] = '_PROFILE_MI_SHOWEMPTY';
$modversion['config'][$i]['description'] = '_PROFILE_MI_SHOWEMPTY_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;
$modversion['config'][$i]['category'] = 'settings';

$i++;
$modversion['config'][$i]['name'] = 'perpage';
$modversion['config'][$i]['title'] = '_MI_SPROFILE_PERPAGE';
$modversion['config'][$i]['description'] = '_MI_SPROFILE_PERPAGE_DSC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '5';
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