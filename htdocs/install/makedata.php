<?php
/**
* Inserts configuration data's
*
* This file is responsible for configuration data's while installing
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		core
* @since		XOOPS
* @author		http://www.xoops.org The XOOPS Project
* @author	   Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
* @version		$Id$
*/
/**
 *
 */
include_once './class/dbmanager.php';

// RMV
// TODO: Shouldn't we insert specific field names??  That way we can use
// the defaults specified in the database...!!!! (and don't have problem
// of missing fields in install file, when add new fields to database)

function make_groups(&$dbm){
    $gruops['XOOPS_GROUP_ADMIN'] = $dbm->insert('groups', " VALUES (0, '".addslashes(_INSTALL_WEBMASTER)."', '".addslashes(_INSTALL_WEBMASTERD)."', 'Admin')");
    $gruops['XOOPS_GROUP_USERS'] = $dbm->insert('groups', " VALUES (0, '".addslashes(_INSTALL_REGUSERS)."', '".addslashes(_INSTALL_REGUSERSD)."', 'User')");
    $gruops['XOOPS_GROUP_ANONYMOUS'] = $dbm->insert('groups', " VALUES (0, '".addslashes(_INSTALL_ANONUSERS)."', '".addslashes(_INSTALL_ANONUSERSD)."', 'Anonymous')");

    if(!$gruops['XOOPS_GROUP_ADMIN'] || !$gruops['XOOPS_GROUP_USERS'] || !$gruops['XOOPS_GROUP_ANONYMOUS']){
        return false;
    }

    return $gruops;
}

function make_data(&$dbm, &$cm, $adminname, $adminlogin_name, $adminpass, $adminmail, $language, $adminsalt, $gruops){

    //$xoopsDB =& Database::getInstance();
    //$dbm = new db_manager;

    $tables = array();

    // data for table 'groups_users_link'

    $dbm->insert('groups_users_link', " VALUES (0, ".$gruops['XOOPS_GROUP_ADMIN'].", 1)");
    $dbm->insert('groups_users_link', " VALUES (0, ".$gruops['XOOPS_GROUP_USERS'].", 1)");

    // data for table 'group_permission'

    $dbm->insert("group_permission", " VALUES (0,".$gruops['XOOPS_GROUP_ADMIN'].",1,1,'module_admin')");
    $dbm->insert("group_permission", " VALUES (0,".$gruops['XOOPS_GROUP_ADMIN'].",1,1, 'module_read')");
    $dbm->insert("group_permission", " VALUES (0,".$gruops['XOOPS_GROUP_USERS'].",1,1,'module_read')");
    $dbm->insert("group_permission", " VALUES (0,".$gruops['XOOPS_GROUP_ANONYMOUS'].",1,1,'module_read')");

	$dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ADMIN'].",1,1,'system_admin')");
    $dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ADMIN'].",2,1,'system_admin')");
    $dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ADMIN'].",3,1,'system_admin')");
    $dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ADMIN'].",4,1,'system_admin')");
    $dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ADMIN'].",5,1,'system_admin')");
    $dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ADMIN'].",6,1,'system_admin')");
    $dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ADMIN'].",7,1,'system_admin')");
    $dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ADMIN'].",8,1,'system_admin')");
    $dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ADMIN'].",9,1,'system_admin')");
    $dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ADMIN'].",10,1,'system_admin')");
    $dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ADMIN'].",11,1,'system_admin')");
    $dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ADMIN'].",12,1,'system_admin')");
    $dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ADMIN'].",13,1,'system_admin')");
    $dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ADMIN'].",14,1,'system_admin')");
    $dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ADMIN'].",15,1,'system_admin')");
    $dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ADMIN'].",16,1,'system_admin')");
    $dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ADMIN'].",17,1,'system_admin')");
    $dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ADMIN'].",18,1,'system_admin')");
    $dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ADMIN'].",19,1,'system_admin')");
	$dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ADMIN'].",20,1,'system_admin')");
	$dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ADMIN'].",1,1,'group_manager')");
    $dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ADMIN'].",2,1,'group_manager')");
    $dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ADMIN'].",3,1,'group_manager')");
	$dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ADMIN'].",1,1,'content_read')");
    $dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_USERS'].",1,1,'content_read')");
    $dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ANONYMOUS'].",1,1,'content_read')");
    $dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ADMIN'].",1,1,'content_admin')");
    // data for table 'banner'

    $dbm->insert("banner", " (bid, cid, imptotal, impmade, clicks, imageurl, clickurl, date, htmlcode) VALUES (1, 1, 0, 1, 0, '".XOOPS_URL."/images/banners/impresscms_banner.gif', '"._INSTALL_LOCAL_SITE."', 1008813250, '')");
    $dbm->insert("banner", " (bid, cid, imptotal, impmade, clicks, imageurl, clickurl, date, htmlcode) VALUES (2, 1, 0, 1, 0, '".XOOPS_URL."/images/banners/impresscms_banner_2.gif', 'http://www.impresscms.org/', 1008813250, '')");
    $dbm->insert("banner", " (bid, cid, imptotal, impmade, clicks, imageurl, clickurl, date, htmlcode) VALUES (3, 1, 0, 1, 0, '".XOOPS_URL."/images/banners/banner.swf', 'http://www.impresscms.org/', 1008813250, '')");
    $dbm->insert("banner", " (bid, cid, imptotal, impmade, clicks, imageurl, clickurl, date, htmlcode) VALUES (4, 1, 0, 1, 0, '".XOOPS_URL."/images/banners/impresscms_banner_3.gif', '"._INSTALL_LOCAL_SITE."', 1008813250, '')");
    // default theme

    //Image Category to admin Logos
    $dbm->insert("imagecategory", " (imgcat_id, imgcat_name, imgcat_maxsize, imgcat_maxwidth, imgcat_maxheight, imgcat_display, imgcat_weight, imgcat_type, imgcat_storetype) VALUES (1, 'Logos', 350000, 350, 80, 1, 0, 'C', 'file')");
    $dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ADMIN'].",1,1,'imgcat_write')");
    $dbm->insert("group_permission", " VALUES(0,".$gruops['XOOPS_GROUP_ADMIN'].",1,1,'imgcat_read')");
    //Default logo used in the admin
   $dbm->insert("image", " (image_id, image_name, image_nicename, image_mimetype, image_created, image_display, image_weight, imgcat_id) VALUES (1, 'img482278e29e81c.png', 'ImpressCMS', 'image/png', ".time().", 1, 0, 1)");

    $time = time();
    $dbm->insert('tplset', " VALUES (1, 'default', 'ImpressCMS Default Template Set', '', ".$time.")");

    // system modules

    if ( file_exists('../modules/system/language/'.$language.'/modinfo.php') ) {
        include '../modules/system/language/'.$language.'/modinfo.php';
    } else {
        include '../modules/system/language/english/modinfo.php';
        $language = 'english';
    }

    $modversion = array();
    include_once '../modules/system/xoops_version.php';
    $time = time();

	// RMV-NOTIFY (updated for extra column in table)
    $dbm->insert("modules", " VALUES (1, '"._MI_SYSTEM_NAME."', 120, ".$time.", 0, 1, 'system', 0, 1, 0, 0, 0, 0, 1)");

    foreach ($modversion['templates'] as $tplfile) {
        if ($fp = fopen('../modules/system/templates/'.$tplfile['file'], 'r')) {
            $newtplid = $dbm->insert('tplfile', " VALUES (0, 1, 'system', 'default', '".addslashes($tplfile['file'])."', '".addslashes($tplfile['description'])."', ".$time.", ".$time.", 'module')");
            //$newtplid = $xoopsDB->getInsertId();
            $tplsource = fread($fp, filesize('../modules/system/templates/'.$tplfile['file']));
            fclose($fp);
            $dbm->insert('tplsource', " (tpl_id, tpl_source) VALUES (".$newtplid.", '".addslashes($tplsource)."')");
        }
    }

    foreach ($modversion['blocks'] as $func_num => $newblock) {
        if ($fp = fopen('../modules/system/templates/blocks/'.$newblock['template'], 'r')) {
            if (in_array($newblock['template'], array('system_block_user.html', 'system_block_login.html', 'system_block_mainmenu.html', 'system_block_socialbookmark.html', 'system_block_themes.html', 'system_block_search.html'))) {
                $visible = 1;
            } else {
                $visible = 0;
            }
            if (in_array($newblock['template'], array('system_block_search.html'))) {
            $canvaspos = 2;
            } elseif (in_array($newblock['template'], array('system_block_socialbookmark.html'))) {
            $canvaspos = 7;
            } else {
            $canvaspos = 1;
            }
            $options = !isset($newblock['options']) ? '' : trim($newblock['options']);
            $edit_func = !isset($newblock['edit_func']) ? '' : trim($newblock['edit_func']);
            //$newbid = $dbm->insert('newblocks', " VALUES (0, 1, ".$func_num.", '".addslashes($options)."', '".addslashes($newblock['name'])."', '".addslashes($newblock['name'])."', '', ".$canvaspos.", 0, ".$visible.", 'S', 'H', 1, 'system', '".addslashes($newblock['file'])."', '".addslashes($newblock['show_func'])."', '".addslashes($edit_func)."', '".addslashes($newblock['template'])."', 0, ".$time.")");

            # Adding dynamic block area/position system - TheRpLima - 2007-10-21
            #$newbid = $dbm->insert('newblocks', " VALUES (0, 1, ".$func_num.", '".addslashes($options)."', '".addslashes($newblock['name'])."', '".addslashes($newblock['name'])."', '', 0, 0, ".$visible.", 'S', 'H', 1, 'system', '".addslashes($newblock['file'])."', '".addslashes($newblock['show_func'])."', '".addslashes($edit_func)."', '".addslashes($newblock['template'])."', 0, ".$time.")");
            $newbid = $dbm->insert('newblocks', " VALUES (0, 1, ".$func_num.", '".addslashes($options)."', '".addslashes($newblock['name'])."', '".addslashes($newblock['name'])."', '', ".$canvaspos.", 0, ".$visible.", 'S', 'H', 1, 'system', '".addslashes($newblock['file'])."', '".addslashes($newblock['show_func'])."', '".addslashes($edit_func)."', '".addslashes($newblock['template'])."', 0, ".$time.")");

            //$newbid = $xoopsDB->getInsertId();
            $newtplid = $dbm->insert('tplfile', " VALUES (0, ".$newbid.", 'system', 'default', '".addslashes($newblock['template'])."', '".addslashes($newblock['description'])."', ".$time.", ".$time.", 'block')");
            //$newtplid = $xoopsDB->getInsertId();
            $tplsource = fread($fp, filesize('../modules/system/templates/blocks/'.$newblock['template']));
            fclose($fp);
            $dbm->insert('tplsource', " (tpl_id, tpl_source) VALUES (".$newtplid.", '".addslashes($tplsource)."')");
            $dbm->insert("group_permission", " VALUES (0, ".$gruops['XOOPS_GROUP_ADMIN'].", ".$newbid.", 1, 'block_read')");
            //$dbm->insert("group_permission", " VALUES (0, ".$gruops['XOOPS_GROUP_ADMIN'].", ".$newbid.", 'xoops_blockadmiin')");
            $dbm->insert("group_permission", " VALUES (0, ".$gruops['XOOPS_GROUP_USERS'].", ".$newbid.", 1, 'block_read')");
            $dbm->insert("group_permission", " VALUES (0, ".$gruops['XOOPS_GROUP_ANONYMOUS'].", ".$newbid.", 1, 'block_read')");
        }
    }
	// adding welcome custom block visible for webmasters
    $welcome_webmaster_filename = 'language/' . $language . '/welcome_webmaster.tpl';
    if (!file_exists($welcome_webmaster_filename)) {
    	$welcome_webmaster_filename = 'language/english/welcome_webmaster.tpl';
    }
    if ($fp = fopen($welcome_webmaster_filename, 'r')) {
    	$tplsource = fread($fp, filesize('language/' . $language . '/welcome_webmaster.tpl'));
    	fclose($fp);
		$newbid = $dbm->insert('newblocks', " VALUES (0, 0, 0, '', 'Custom Block (Auto Format + smilies)', '" . addslashes(WELCOME_WEBMASTER) . "', '" . addslashes($tplsource) . "', 4, 0, 1, 'C', 'S', 1, '', '', '', '', '', 0, ".$time.")");
		$dbm->insert("group_permission", " VALUES (0, ".$gruops['XOOPS_GROUP_ADMIN'].", ".$newbid.", 1, 'block_read')");
    }
	// adding welcome custom block visible for anonymous
    $welcome_anonymous_filename = 'language/' . $language . '/welcome_anonymous.tpl';
    if (!file_exists($welcome_anonymous_filename)) {
    	$welcome_anonymous_filename = 'language/english/welcome_anonymous.tpl';
    }
    if ($fp = fopen($welcome_anonymous_filename, 'r')) {
    	$tplsource = fread($fp, filesize('language/' . $language . '/welcome_anonymous.tpl'));
    	fclose($fp);
		$newbid = $dbm->insert('newblocks', " VALUES (0, 0, 0, '', 'Custom Block (Auto Format + smilies)', '" . addslashes(WELCOME_ANONYMOUS) . "', '" . addslashes($tplsource) . "', 4, 0, 1, 'C', 'S', 1, '', '', '', '', '', 0, ".$time.")");
		$dbm->insert("group_permission", " VALUES (0, ".$gruops['XOOPS_GROUP_ANONYMOUS'].", ".$newbid.", 1, 'block_read')");
    }

    // data for table 'users'
    $mainSalt = XOOPS_DB_SALT;
    $temp = icms_encryptPass($adminpass, $adminsalt, $mainSalt);
    $regdate = time();
    //$dbadminname= addslashes($adminname);
	// RMV-NOTIFY (updated for extra columns in user table)
    $dbm->insert('users', " VALUES (1,'','".addslashes($adminname)."','".addslashes($adminmail)."','".XOOPS_URL."/','blank.gif','".$regdate."','','','',0,'','','','','".$temp."',0,0,7,5,'impresstheme','0.0',".time().",'thread',0,1,0,'','','','0','".addslashes($language)."', '', '".addslashes($adminsalt)."', 0, 0, 1, '".addslashes($adminlogin_name)."')");


    // data for table 'block_module_link'

    $sql = 'SELECT bid, side FROM '.$dbm->prefix('newblocks');
    $result = $dbm->query($sql);

    while ($myrow = $dbm->fetchArray($result)) {
        # Adding dynamic block area/position system - TheRpLima - 2007-10-21
    	#if ($myrow['side'] == 0) {
        if ($myrow['side'] == 1 OR $myrow['side'] == 2 OR $myrow['side'] == 7) {
            $dbm->insert("block_module_link", " VALUES (".$myrow['bid'].", 0, 0)");
        } else {
            $dbm->insert("block_module_link", " VALUES (".$myrow['bid'].", 0, 1)");
        }
    }

    	// Data for table 'config'

	$i=1; // sets auto increment for config values (incremented using $i++ after each value.)
	$p=0; // sets auto increment for config position (the order in which the option is displayed in the form)
	$ci=1; // sets auto increment for configoption values (incremented using $ci++ after each value.)

	// Data for Config Category 1 (System Preferences)
	$c=1; // sets config category id
	$p=0; // sets auto increment for config position (the order in which the option is displayed in the form)
	$dbm->insert('config', " VALUES ($i, 0, $c, 'sitename', '_MD_AM_SITENAME', '"._LOCAOL_STNAME."', '_MD_AM_SITENAMEDSC', 'textbox', 'text', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'slogan', '_MD_AM_SLOGAN', '"._LOCAL_SLOCGAN." ', '_MD_AM_SLOGANDSC', 'textbox', 'text', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'adminmail', '_MD_AM_ADMINML', '".addslashes($adminmail)."', '_MD_AM_ADMINMLDSC', 'textbox', 'text', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'language', '_MD_AM_LANGUAGE', '".addslashes($language)."', '_MD_AM_LANGUAGEDSC', 'language', 'other', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'startpage', '_MD_AM_STARTPAGE', 'a:3:{i:1;s:3:\"1-1\";i:2;s:3:\"1-1\";i:3;s:3:\"1-1\";}', '_MD_AM_STARTPAGEDSC', 'startpage', 'array', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'server_TZ', '_MD_AM_SERVERTZ', '0', '_MD_AM_SERVERTZDSC', 'timezone', 'float', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'default_TZ', '_MD_AM_DEFAULTTZ', '0', '_MD_AM_DEFAULTTZDSC', 'timezone', 'float', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'use_ext_date', '_MD_AM_EXT_DATE', '"._EXT_DATE_FUNC."', '_MD_AM_EXT_DATEDSC', 'yesno', 'int', $p)");
 	$i++;
	$p++;
   	$dbm->insert('config', " VALUES ($i, 0, $c, 'theme_set', '_MD_AM_DTHEME', 'impresstheme', '_MD_AM_DTHEMEDSC', 'theme', 'other', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'theme_fromfile', '_MD_AM_THEMEFILE', '0', '_MD_AM_THEMEFILEDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
  	$dbm->insert('config', " VALUES ($i, 0, $c, 'theme_set_allowed', '_MD_AM_THEMEOK', '".serialize(array('impresstheme', 'impresstheme_light', 'iTheme'))."', '_MD_AM_THEMEOKDSC', 'theme_multi', 'array', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'template_set', '_MD_AM_DTPLSET', 'default', '_MD_AM_DTPLSETDSC', 'tplset', 'other', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'editor_default', '_MD_AM_EDITOR_DEFAULT', 'default', '_MD_AM_EDITOR_DEFAULT_DESC', 'editor', 'text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'editor_enabled_list', '_MD_AM_EDITOR_ENABLED_LIST', '".addslashes(serialize(array('default')))."', '_MD_AM_EDITOR_ENABLED_LIST_DESC', 'editor_multi', 'array', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'anonymous', '_MD_AM_ANONNAME', '".addslashes(_INSTALL_ANON)."', '_MD_AM_ANONNAMEDSC', 'textbox', 'text', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'gzip_compression', '_MD_AM_USEGZIP', '0', '_MD_AM_USEGZIPDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'usercookie', '_MD_AM_USERCOOKIE', 'icms_user', '_MD_AM_USERCOOKIEDSC', 'textbox', 'text', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'use_mysession', '_MD_AM_USEMYSESS', '0', '_MD_AM_USEMYSESSDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'session_name', '_MD_AM_SESSNAME', 'icms_session', '_MD_AM_SESSNAMEDSC', 'textbox', 'text', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'session_expire', '_MD_AM_SESSEXPIRE', '15', '_MD_AM_SESSEXPIREDSC', 'textbox', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'debug_mode', '_MD_AM_DEBUGMODE', '1', '_MD_AM_DEBUGMODEDSC', 'select', 'int', $p)");
	// Insert data for Config Options in selection field. (must be placed before $i++)
	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_DEBUGMODE0', 0, $i)");
	$ci++;
	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_DEBUGMODE1', 1, $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_DEBUGMODE2', 2, $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_DEBUGMODE3', 3, $i)");
	$ci++;
	// ----------
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'banners', '_MD_AM_BANNERS', '1', '_MD_AM_BANNERSDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'closesite', '_MD_AM_CLOSESITE', '0', '_MD_AM_CLOSESITEDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'closesite_okgrp', '_MD_AM_CLOSESITEOK', '".addslashes(serialize(array('1')))."', '_MD_AM_CLOSESITEOKDSC', 'group_multi', 'array', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'closesite_text', '_MD_AM_CLOSESITETXT', '"._INSTALL_L165."', '_MD_AM_CLOSESITETXTDSC', 'textarea', 'text', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'my_ip', '_MD_AM_MYIP', '127.0.0.1', '_MD_AM_MYIPDSC', 'textbox', 'text', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'use_ssl', '_MD_AM_USESSL', '0', '_MD_AM_USESSLDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'sslpost_name', '_MD_AM_SSLPOST', 'icms_ssl', '_MD_AM_SSLPOSTDSC', 'textbox', 'text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'sslloginlink', '_MD_AM_SSLLINK', 'https://', '_MD_AM_SSLLINKDSC', 'textbox', 'text', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'com_mode', '_MD_AM_COMMODE', 'nest', '_MD_AM_COMMODEDSC', 'select', 'text', $p)");
	// Insert data for Config Options in selection field. (must be placed before $i++)
    	$dbm->insert('configoption', " VALUES ($ci, '_NESTED', 'nest', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_FLAT', 'flat', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_THREADED', 'thread', $i)");
	$ci++;
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'com_order', '_MD_AM_COMORDER', '0', '_MD_AM_COMORDERDSC', 'select', 'int', $p)");
	// Insert data for Config Options in selection field. (must be placed before $i++)
    	$dbm->insert('configoption', " VALUES ($ci, '_OLDESTFIRST', '0', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_NEWESTFIRST', '1', $i)");
	$ci++;
	// ----------
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'use_captchaf', '_MD_AM_USECAPTCHAFORM', 1, '_MD_AM_USECAPTCHAFORMDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'enable_badips', '_MD_AM_DOBADIPS', '0', '_MD_AM_DOBADIPSDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'bad_ips', '_MD_AM_BADIPS', '".addslashes(serialize(array('127.0.0.1')))."', '_MD_AM_BADIPSDSC', 'textarea', 'array', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'module_cache', '_MD_AM_MODCACHE', '', '_MD_AM_MODCACHEDSC', 'module_cache', 'array', $p)");

	// Data for Config Category 2 (User Preferences)
	$c=2; // sets config category id
	$i++;
	$p=0; // reset position increment to 0 for new category id
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'allow_register', '_MD_AM_ALLOWREG', 1, '_MD_AM_ALLOWREGDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'minpass', '_MD_AM_MINPASS', '5', '_MD_AM_MINPASSDSC', 'textbox', 'int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'pass_level', '_MD_AM_PASSLEVEL', '20', '_MD_AM_PASSLEVEL_DESC', 'select', 'int', $p)");
		// Insert data for Config Options in selection field. (must be placed before $i++)
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_PASSLEVEL1', '20', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_PASSLEVEL2', '40', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_PASSLEVEL3', '60', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_PASSLEVEL4', '80', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_PASSLEVEL5', '95', $i)");
	$ci++;
	// ----------
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'minuname', '_MD_AM_MINUNAME', '3', '_MD_AM_MINUNAMEDSC', 'textbox', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'maxuname', '_MD_AM_MAXUNAME', '20', '_MD_AM_MAXUNAMEDSC', 'textbox', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'use_captcha', '_MD_AM_USECAPTCHA', 1, '_MD_AM_USECAPTCHADSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'welcome_msg', '_MD_AM_WELCOMEMSG', '0', '_MD_AM_WELCOMEMSGDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'welcome_msg_content', '_MD_AM_WELCOMEMSG_CONTENT', '".addslashes(_WELCOME_MSG_CONTENT)."', '_MD_AM_WELCOMEMSG_CONTENTDSC', 'textarea', 'text', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'allow_chgmail', '_MD_AM_ALLWCHGMAIL', '0', '_MD_AM_ALLWCHGMAILDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'allwshow_sig', '_MD_AM_ALLWSHOWSIG', '1', '_MD_AM_ALLWSHOWSIGDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'allow_htsig', '_MD_AM_ALLWHTSIG', '1', '_MD_AM_ALLWHTSIGDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'sig_max_length', '_MD_AM_SIGMAXLENGTH', '255', '_MD_AM_SIGMAXLENGTHDSC', 'textbox', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'new_user_notify', '_MD_AM_NEWUNOTIFY', '1', '_MD_AM_NEWUNOTIFYDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'new_user_notify_group', '_MD_AM_NOTIFYTO', ".$gruops['XOOPS_GROUP_ADMIN'].", '_MD_AM_NOTIFYTODSC', 'group', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'activation_type', '_MD_AM_ACTVTYPE', '0', '_MD_AM_ACTVTYPEDSC', 'select', 'int', $p)");
	// Insert data for Config Options in selection field. (must be placed before $i++)
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_USERACTV', '0', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_AUTOACTV', '1', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_ADMINACTV', '2', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_REGINVITE', '3', $i)");
	$ci++;
	// ----------
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'activation_group', '_MD_AM_ACTVGROUP', ".$gruops['XOOPS_GROUP_ADMIN'].", '_MD_AM_ACTVGROUPDSC', 'group', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'uname_test_level', '_MD_AM_UNAMELVL', '0', '_MD_AM_UNAMELVLDSC', 'select', 'int', $p)");
	// Insert data for Config Options in selection field. (must be placed before $i++)
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_STRICT', '0', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_MEDIUM', '1', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_LIGHT', '2', $i)");
	$ci++;
	// ----------
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'avatar_allow_upload', '_MD_AM_AVATARALLOW', '0', '_MD_AM_AVATARALWDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'avatar_allow_gravatar', '_MD_AM_GRAVATARALLOW', '1', '_MD_AM_GRAVATARALWDSC', 'yesno', 'int', $p)");
/* the avatar resizer shall later be included
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'avatar_auto_resize', '_MD_AM_AUTORESIZE', '0', '_MD_AM_AUTORESIZE_DESC', 'yesno', 'int', $p)");
*/
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'avatar_minposts', '_MD_AM_AVATARMP', '0', '_MD_AM_AVATARMPDSC', 'textbox', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'avatar_width', '_MD_AM_AVATARW', '80', '_MD_AM_AVATARWDSC', 'textbox', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'avatar_height', '_MD_AM_AVATARH', '80', '_MD_AM_AVATARHDSC', 'textbox', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'avatar_maxsize', '_MD_AM_AVATARMAX', '35000', '_MD_AM_AVATARMAXDSC', 'textbox', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'self_delete', '_MD_AM_SELFDELETE', '0', '_MD_AM_SELFDELETEDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'rank_width', '_MD_AM_RANKW', '120', '_MD_AM_RANKWDSC', 'textbox', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'rank_height', '_MD_AM_RANKH', '120', '_MD_AM_RANKHDSC', 'textbox', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'rank_maxsize', '_MD_AM_RANKMAX', '35000', '_MD_AM_RANKMAXDSC', 'textbox', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'bad_unames', '_MD_AM_BADUNAMES', '".addslashes(serialize(array('webmaster', '^impresscms', '^admin')))."', '_MD_AM_BADUNAMESDSC', 'textarea', 'array', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'bad_emails', '_MD_AM_BADEMAILS', '".addslashes(serialize(array('impresscms.org$')))."', '_MD_AM_BADEMAILSDSC', 'textarea', 'array', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'remember_me', '_MD_AM_REMEMBERME', '0', '_MD_AM_REMEMBERMEDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'reg_dispdsclmr', '_MD_AM_DSPDSCLMR', 1, '_MD_AM_DSPDSCLMRDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'reg_disclaimer', '_MD_AM_REGDSCLMR', '".addslashes(_INSTALL_DISCLMR)."', '_MD_AM_REGDSCLMRDSC', 'textarea', 'text', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'priv_dpolicy', '_MD_AM_PRIVDPOLICY', 0, '_MD_AM_PRIVDPOLICYDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'priv_policy', '_MD_AM_PRIVPOLICY', '".addslashes(_INSTALL_PRIVPOLICY)."', '_MD_AM_PRIVPOLICYDSC', 'textarea', 'text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'allow_annon_view_prof', '_MD_AM_ALLOW_ANONYMOUS_VIEW_PROFILE', '0', '_MD_AM_ALLOW_ANONYMOUS_VIEW_PROFILE_DESC', 'yesno', 'int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'enc_type', '_MD_AM_ENC_TYPE', '1', '_MD_AM_ENC_TYPEDSC', 'select', 'int', $p)");
	// Insert data for Config Options in selection field. (must be placed before $i++)
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_ENC_MD5', '0', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_ENC_SHA256', '1', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_ENC_SHA384', '2', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_ENC_SHA512', '3', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_ENC_RIPEMD128', '4', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_ENC_RIPEMD160', '5', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_ENC_WHIRLPOOL', '6', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_ENC_HAVAL1284', '7', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_ENC_HAVAL1604', '8', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_ENC_HAVAL1924', '9', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_ENC_HAVAL2244', '10', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_ENC_HAVAL2564', '11', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_ENC_HAVAL1285', '12', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_ENC_HAVAL1605', '13', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_ENC_HAVAL1925', '14', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_ENC_HAVAL2245', '15', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_ENC_HAVAL2565', '16', $i)");
	$ci++;
	// ----------

	// Data for Config Category 3 (Meta & Footer Preferences)
	$c=3; // sets config category id
	$i++;
	$p=0; // reset position increment to 0 for new category id
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'meta_keywords', '_MD_AM_METAKEY', 'community management system, CMS, content management, social networking, community, blog, support, modules, add-ons, themes', '_MD_AM_METAKEYDSC', 'textarea', 'text', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'meta_description', '_MD_AM_METADESC', 'ImpressCMS is a dynamic Object Oriented based open source portal script written in PHP.', '_MD_AM_METADESCDSC', 'textarea', 'text', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'meta_robots', '_MD_AM_METAROBOTS', 'index,follow', '_MD_AM_METAROBOTSDSC', 'select', 'text', $p)");
	// Insert data for Config Options in selection field. (must be placed before $i++)
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_INDEXFOLLOW', 'index,follow', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_NOINDEXFOLLOW', 'noindex,follow', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_INDEXNOFOLLOW', 'index,nofollow', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_NOINDEXNOFOLLOW', 'noindex,nofollow', $i)");
	$ci++;
	// ----------
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'meta_rating', '_MD_AM_METARATING', 'general', '_MD_AM_METARATINGDSC', 'select', 'text', $p)");
	// Insert data for Config Options in selection field. (must be placed before $i++)
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_METAOGEN', 'general', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_METAO14YRS', '14 years', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_METAOREST', 'restricted', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_METAOMAT', 'mature', $i)");
	$ci++;
	// ----------
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'meta_author', '_MD_AM_METAAUTHOR', 'ImpressCMS', '_MD_AM_METAAUTHORDSC', 'textbox', 'text', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'meta_copyright', '_MD_AM_METACOPYR', 'Copyright &copy; 2007-" . date('Y', time()) . "', '_MD_AM_METACOPYRDSC', 'textbox', 'text', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'google_meta', '_MD_AM_METAGOOGLE', '', '_MD_AM_METAGOOGLE_DESC', 'textbox', 'text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'footer', '_MD_AM_FOOTER', '"._LOCAL_FOOTER."', '_MD_AM_FOOTERDSC', 'textarea', 'text', $p)");
	$i++;
	$p++;
    $dbm->insert('config', " VALUES ($i, 0, $c, 'use_google_analytics', '_MD_AM_USE_GOOGLE_ANA', 0, '_MD_AM_USE_GOOGLE_ANA_DESC', 'yesno', 'int', $p)");
	$i++;
	$p++;
    $dbm->insert('config', " VALUES ($i, 0, $c, 'google_analytics', '_MD_AM_GOOGLE_ANA', '', '_MD_AM_GOOGLE_ANA_DESC', 'textbox', 'text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'footadm', '_MD_AM_FOOTADM', '"._LOCAL_FOOTER."', '_MD_AM_FOOTADM_DESC', 'textarea', 'text', $p)");

	// Data for Config Category 4 (Badword Preferences)
	$c=4; // sets config category id
	$i++;
	$p=0; // reset position increment to 0 for new category id
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'censor_enable', '_MD_AM_DOCENSOR', '0', '_MD_AM_DOCENSORDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'censor_words', '_MD_AM_CENSORWRD', '".addslashes(serialize(array('fuck', 'shit', 'cunt', 'wanker', 'bastard')))."', '_MD_AM_CENSORWRDDSC', 'textarea', 'array', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'censor_replace', '_MD_AM_CENSORRPLC', '"._LOCAL_SENSORTXT."', '_MD_AM_CENSORRPLCDSC', 'textbox', 'text', $p)");

	// Data for Config Category 5 (Search Preferences)
	$c=5; // sets config category id
	$i++;
	$p=0; // reset position increment to 0 for new category id
	$dbm->insert('config', " VALUES ($i, 0, $c, 'enable_search', '_MD_AM_DOSEARCH', '1', '_MD_AM_DOSEARCHDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'keyword_min', '_MD_AM_MINSEARCH', '5', '_MD_AM_MINSEARCHDSC', 'textbox', 'int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'search_user_date', '_MD_AM_SEARCH_USERDATE', '1', '_MD_AM_SEARCH_USERDATE', 'yesno', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'search_no_res_mod', '_MD_AM_SEARCH_NO_RES_MOD', '1', '_MD_AM_SEARCH_NO_RES_MODDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
    	$dbm->insert('config', " VALUES ($i, 0, $c, 'search_per_page', '_MD_AM_SEARCH_PER_PAGE', '20', '_MD_AM_SEARCH_PER_PAGEDSC', 'textbox', 'int', $p)");

	// Data for Config Category 6 (Mail Settings)
	$c=6; // sets config category id
	$i++;
	$p=0; // reset position increment to 0 for new category id
	$dbm->insert('config', " VALUES ($i,0,$c,'from','_MD_AM_MAILFROM','','_MD_AM_MAILFROMDESC','textbox','text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i,0,$c,'fromname','_MD_AM_MAILFROMNAME','','_MD_AM_MAILFROMNAMEDESC','textbox','text', $p)");
	$i++;
	$p++;
	// RMV-NOTIFY... Need to specify which user is sender of notification PM
	$dbm->insert('config', " VALUES ($i,0,$c,'fromuid','_MD_AM_MAILFROMUID','1','_MD_AM_MAILFROMUIDDESC','user','int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i,0,$c,'mailmethod','_MD_AM_MAILERMETHOD','mail','_MD_AM_MAILERMETHODDESC','select','text', $p)");
	// Insert data for Config Options in selection field. (must be placed before $i++)
    	$dbm->insert('configoption', " VALUES ($ci, 'PHP mail()','mail', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, 'sendmail','sendmail', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, 'SMTP','smtp', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, 'SMTPAuth','smtpauth', $i)");
	$ci++;
	// ----------
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i,0,$c,'smtphost','_MD_AM_SMTPHOST','a:1:{i:0;s:0:\"\";}', '_MD_AM_SMTPHOSTDESC','textarea','array', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i,0,$c,'smtpuser','_MD_AM_SMTPUSER','','_MD_AM_SMTPUSERDESC','textbox','text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i,0,$c,'smtppass','_MD_AM_SMTPPASS','','_MD_AM_SMTPPASSDESC','password','text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i,0,$c,'sendmailpath','_MD_AM_SENDMAILPATH','/usr/sbin/sendmail','_MD_AM_SENDMAILPATHDESC','textbox','text', $p)");

	// Data for Config Category 7 (Authentication Settings)
	$c=7; // sets config category id
	$i++;
	$p=0; // reset position increment to 0 for new category id
	$dbm->insert('config', " VALUES ($i,0,$c,'auth_method','_MD_AM_AUTHMETHOD','xoops','_MD_AM_AUTHMETHODDESC','select','text', $p)");
	// Insert data for Config Options in selection field. (must be placed before $i++)
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_AUTH_CONFOPTION_XOOPS', 'xoops', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_AUTH_CONFOPTION_LDAP', 'ldap', $i)");
	$ci++;
    	$dbm->insert('configoption', " VALUES ($ci, '_MD_AM_AUTH_CONFOPTION_AD', 'ads', $i)");
	$ci++;
	// ----------
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i,0,$c,'auth_openid','_MD_AM_AUTHOPENID','0','_MD_AM_AUTHOPENIDDSC','yesno','int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i,0,$c,'ldap_port','_MD_AM_LDAP_PORT','389','_MD_AM_LDAP_PORT','textbox','int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i,0,$c,'ldap_server','_MD_AM_LDAP_SERVER','your directory server','_MD_AM_LDAP_SERVER_DESC','textbox','text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i,0,$c,'ldap_base_dn','_MD_AM_LDAP_BASE_DN','dc=icms,dc=org','_MD_AM_LDAP_BASE_DN_DESC','textbox','text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i,0,$c,'ldap_manager_dn','_MD_AM_LDAP_MANAGER_DN','manager_dn','_MD_AM_LDAP_MANAGER_DN_DESC','textbox','text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i,0,$c,'ldap_manager_pass','_MD_AM_LDAP_MANAGER_PASS','manager_pass','_MD_AM_LDAP_MANAGER_PASS_DESC','password','text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i,0,$c,'ldap_version','_MD_AM_LDAP_VERSION','3','_MD_AM_LDAP_VERSION_DESC','textbox','text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i,0,$c,'ldap_users_bypass','_MD_AM_LDAP_USERS_BYPASS','".serialize(array('admin'))."','_MD_AM_LDAP_USERS_BYPASS_DESC','textarea','array', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i,0,$c,'ldap_loginname_asdn','_MD_AM_LDAP_LOGINNAME_ASDN','uid_asdn','_MD_AM_LDAP_LOGINNAME_ASDN_D','yesno','int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i,0,$c,'ldap_loginldap_attr', '_MD_AM_LDAP_LOGINLDAP_ATTR', 'uid', '_MD_AM_LDAP_LOGINLDAP_ATTR_D', 'textbox', 'text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i,0,$c,'ldap_filter_person','_MD_AM_LDAP_FILTER_PERSON','','_MD_AM_LDAP_FILTER_PERSON_DESC','textbox','text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i,0,$c,'ldap_domain_name','_MD_AM_LDAP_DOMAIN_NAME','mydomain','_MD_AM_LDAP_DOMAIN_NAME_DESC','textbox','text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i,0,$c,'ldap_provisionning','_MD_AM_LDAP_PROVIS','0','_MD_AM_LDAP_PROVIS_DESC','yesno','int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i,0,$c,'ldap_provisionning_group','_MD_AM_LDAP_PROVIS_GROUP','a:1:{i:0;s:1:\"2\";}','_MD_AM_LDAP_PROVIS_GROUP_DSC','group_multi','array', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i,0,$c,'ldap_mail_attr','_MD_AM_LDAP_MAIL_ATTR','mail','_MD_AM_LDAP_MAIL_ATTR_DESC','textbox','text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i,0,$c,'ldap_givenname_attr','_MD_AM_LDAP_GIVENNAME_ATTR','givenname','_MD_AM_LDAP_GIVENNAME_ATTR_DSC','textbox','text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i,0,$c,'ldap_surname_attr','_MD_AM_LDAP_SURNAME_ATTR','sn','_MD_AM_LDAP_SURNAME_ATTR_DESC','textbox','text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i,0,$c,'ldap_field_mapping','_MD_AM_LDAP_FIELD_MAPPING_ATTR','email=mail|name=displayname','_MD_AM_LDAP_FIELD_MAPPING_DESC','textarea','text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i,0,$c,'ldap_provisionning_upd', '_MD_AM_LDAP_PROVIS_UPD', '1', '_MD_AM_LDAP_PROVIS_UPD_DESC', 'yesno', 'int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i,0,$c,'ldap_use_TLS','_MD_AM_LDAP_USETLS','0','_MD_AM_LDAP_USETLS_DESC','yesno','int', $p)");

	// Data for Config Category 8 (Multi Language Settings)
	$c=8; // sets config category id
	$i++;
	$p=0; // reset position increment to 0 for new category id
	$dbm->insert('config', " VALUES ($i, 0, $c,'ml_enable', '_MD_AM_ML_ENABLE', '0', '_MD_AM_ML_ENABLEDEC', 'yesno', 'int', $p)");
	$i++;
	$p++;
 	$dbm->insert('config', " VALUES ($i, 0, $c,'ml_autoselect_enabled', '_MD_AM_ML_AUTOSELECT_ENABLED', '0', '_MD_AM_ML_AUTOSELECT_ENABLED_DESC', 'yesno', 'int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c,'ml_tags', '_MD_AM_ML_TAGS', '"._DEF_LANG_TAGS."', '_MD_AM_ML_TAGSDSC', 'textbox', 'text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c,'ml_names', '_MD_AM_ML_NAMES', '"._DEF_LANG_NAMES."', '_MD_AM_ML_NAMESDSC', 'textbox', 'text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c,'ml_captions', '_MD_AM_ML_CAPTIONS', '"._LOCAL_LANG_NAMES."', '_MD_AM_ML_CAPTIONSDSC', 'textbox', 'text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c,'ml_charset', '_MD_AM_ML_CHARSET', 'UTF-8,UTF-8', '_MD_AM_ML_CHARSETDSC', 'textbox', 'text', $p)");

	// Data for Config Category 9 (Content Manager Settings)
	$c=9; // sets config category id
	$i++;
	$p=0;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'default_page', '_MD_AM_DEFAULT_CONTPAGE', '0', '_MD_AM_DEFAULT_CONTPAGEDSC', 'select_pages', 'int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'show_nav', '_MD_AM_CONT_SHOWNAV', '1', '_MD_AM_CONT_SHOWNAVDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'show_subs', '_MD_AM_CONT_SHOWSUBS', '1', '_MD_AM_CONT_SHOWSUBSDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'show_pinfo', '_MD_AM_CONT_SHOWPINFO', '1', '_MD_AM_CONT_SHOWPINFODSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'num_pages', '_MD_AM_CONT_NUMPAGES', '10', '_MD_AM_CONT_NUMPAGESDSC', 'textbox', 'int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'teaser_length', '_MD_AM_CONT_TEASERLENGTH', '500', '_MD_AM_CONT_TEASERLENGTHDSC', 'textbox', 'int', $p)");


	// Data for Config Category 10 (Personalization Settings)
	$c=10; // sets config category id
	$i++;
	$p=0;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'adm_left_logo', '_MD_AM_LLOGOADM', '/uploads/img482278e29e81c.png', '_MD_AM_LLOGOADM_DESC', 'select_image', 'text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'adm_left_logo_url', '_MD_AM_LLOGOADM_URL', '".XOOPS_URL."/index.php', '_MD_AM_LLOGOADM_URL_DESC', 'textbox', 'text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'adm_left_logo_alt', '_MD_AM_LLOGOADM_ALT', 'ImpressCMS', '_MD_AM_LLOGOADM_ALT_DESC', 'textbox', 'text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'adm_right_logo', '_MD_AM_RLOGOADM', '', '_MD_AM_RLOGOADM_DESC', 'select_image', 'text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'adm_right_logo_url', '_MD_AM_RLOGOADM_URL', '', '_MD_AM_RLOGOADM_URL_DESC', 'textbox', 'text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'adm_right_logo_alt', '_MD_AM_RLOGOADM_ALT', '', '_MD_AM_RLOGOADM_ALT_DESC', 'textbox', 'text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'rss_local', '_MD_AM_RSSLOCAL', '"._MD_AM_RSSLOCALLINK_DESC."', '_MD_AM_RSSLOCAL_DESC', 'textbox', 'text', $p)");
	$i++;
	$p++;
    $dbm->insert('config', " VALUES ($i, 0, $c, 'editre_block', '_MD_AM_EDITREMOVEBLOCK', '1', '_MD_AM_EDITREMOVEBLOCKDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
    $dbm->insert('config', " VALUES ($i, 0, $c, 'multi_login', '_MD_AM_MULTLOGINPREVENT', '0', '_MD_AM_MULTLOGINPREVENTDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'multi_login_msg', '_MD_AM_MULTLOGINMSG', '"._MD_AM_MULTLOGINMSG_TXT."', '_MD_AM_MULTLOGINMSG_DESC', 'textarea', 'text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'email_protect', '_MD_AM_EMAILPROTECT', '0', '_MD_AM_EMAILPROTECTDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'email_font', '_MD_AM_EMAILTTF', 'arial.ttf', '_MD_AM_EMAILTTF_DESC', 'select_font', 'text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'email_font_len', '_MD_AM_EMAILLEN', '12', '_MD_AM_EMAILLEN_DESC', 'textbox', 'int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'email_cor', '_MD_AM_EMAILCOLOR', '#000000', '_MD_AM_EMAILCOLOR_DESC', 'color', 'text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'email_shadow', '_MD_AM_EMAILSHADOW', '#cccccc', '_MD_AM_EMAILSHADOW_DESC', 'color', 'text', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'shadow_x', '_MD_AM_SHADOWX', '2', '_MD_AM_SHADOWX_DESC', 'textbox', 'int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'shadow_y', '_MD_AM_SHADOWY', '2', '_MD_AM_SHADOWY_DESC', 'textbox', 'int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'shorten_url', '_MD_AM_SHORTURL', '0', '_MD_AM_SHORTURLDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'max_url_long', '_MD_AM_URLLEN', '50', '_MD_AM_URLLEN_DESC', 'textbox', 'int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'pre_chars_left', '_MD_AM_PRECHARS', '35', '_MD_AM_PRECHARS_DESC', 'textbox', 'int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'last_chars_left', '_MD_AM_LASTCHARS', '10', '_MD_AM_LASTCHARS_DESC', 'textbox', 'int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'show_impresscms_menu', '_MD_AM_SHOW_ICMSMENU', '1', '_MD_AM_SHOW_ICMSMENU_DESC', 'yesno', 'int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'use_hidden', '_MD_AM_HIDDENCONTENT', '0', '_MD_AM_HIDDENCONTENTDSC', 'yesno', 'int', $p)");
	$i++;
	$p++;
	$dbm->insert('config', " VALUES ($i, 0, $c, 'pagstyle', '_MD_AM_PAGISTYLE', 'default', '_MD_AM_PAGISTYLE_DESC', 'select_paginati', 'text', $p)");

    return $gruops;
}

?>