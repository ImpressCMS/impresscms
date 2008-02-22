<?php
// $Id: makedata.php 592 2006-06-30 02:33:23Z skalpa $
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

function make_data(&$dbm, &$cm, $adminname, $adminpass, $adminmail, $language, $gruops){

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

    // data for table 'banner'

    $dbm->insert("banner", " (bid, cid, imptotal, impmade, clicks, imageurl, clickurl, date, htmlcode) VALUES (1, 1, 0, 1, 0, '".XOOPS_URL."/images/banners/impresscms_banner.gif', 'http://www.impresscms.org/', 1008813250, '')");
    $dbm->insert("banner", " (bid, cid, imptotal, impmade, clicks, imageurl, clickurl, date, htmlcode) VALUES (2, 1, 0, 1, 0, '".XOOPS_URL."/images/banners/impresscms_banner_2.gif', 'http://www.impresscms.org/', 1008813250, '')");
    $dbm->insert("banner", " (bid, cid, imptotal, impmade, clicks, imageurl, clickurl, date, htmlcode) VALUES (3, 1, 0, 1, 0, '".XOOPS_URL."/images/banners/banner.swf', 'http://www.impresscms.org/', 1008813250, '')");
    $dbm->insert("banner", " (bid, cid, imptotal, impmade, clicks, imageurl, clickurl, date, htmlcode) VALUES (4, 1, 0, 1, 0, '".XOOPS_URL."/images/banners/impresscms_banner_3.gif', 'http://www.impresscms.org/', 1008813250, '')");
    // default theme

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
    $dbm->insert("modules", " VALUES (1, '"._MI_SYSTEM_NAME."', 100, ".$time.", 0, 1, 'system', 0, 1, 0, 0, 0, 0)");

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
            if (in_array($newblock['template'], array('system_block_user.html', 'system_block_login.html', 'system_block_mainmenu.html'))) {
                $visible = 1;
            } else {
                $visible = 0;
            }
            $canvaspos = 1;
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

    // data for table 'users'

    $temp = md5($adminpass);
    $regdate = time();
    //$dbadminname= addslashes($adminname);
	// RMV-NOTIFY (updated for extra columns in user table)
    $dbm->insert('users', " VALUES (1,'','".addslashes($adminname)."','".addslashes($adminmail)."','".XOOPS_URL."/','blank.gif','".$regdate."','','','',0,'','','','','".$temp."',0,0,7,5,'impresstheme','0.0',".time().",'thread',0,1,0,'','','',0)");


    // data for table 'block_module_link'

    $sql = 'SELECT bid, side FROM '.$dbm->prefix('newblocks');
    $result = $dbm->query($sql);

    while ($myrow = $dbm->fetchArray($result)) {
        # Adding dynamic block area/position system - TheRpLima - 2007-10-21
    	#if ($myrow['side'] == 0) {
        if ($myrow['side'] == 1) {
            $dbm->insert("block_module_link", " VALUES (".$myrow['bid'].", 0)");
        } else {
            $dbm->insert("block_module_link", " VALUES (".$myrow['bid'].", -1)");
        }
    }

    // data for table 'config'

    $dbm->insert('config', " VALUES (1, 0, 1, 'sitename', '_MD_AM_SITENAME', 'ImpressCMS', '_MD_AM_SITENAMEDSC', 'textbox', 'text', 0)");
    $dbm->insert('config', " VALUES (2, 0, 1, 'slogan', '_MD_AM_SLOGAN', 'Make a lasting impression ', '_MD_AM_SLOGANDSC', 'textbox', 'text', 2)");
    $dbm->insert('config', " VALUES (3, 0, 1, 'language', '_MD_AM_LANGUAGE', '".addslashes($language)."', '_MD_AM_LANGUAGEDSC', 'language', 'other', 4)");
    $dbm->insert('config', " VALUES (4, 0, 1, 'startpage', '_MD_AM_STARTPAGE', '--', '_MD_AM_STARTPAGEDSC', 'startpage', 'other', 6)");
    $dbm->insert('config', " VALUES (5, 0, 1, 'server_TZ', '_MD_AM_SERVERTZ', '0', '_MD_AM_SERVERTZDSC', 'timezone', 'float', 8)");
    $dbm->insert('config', " VALUES (6, 0, 1, 'default_TZ', '_MD_AM_DEFAULTTZ', '0', '_MD_AM_DEFAULTTZDSC', 'timezone', 'float', 10)");
    $dbm->insert('config', " VALUES (7, 0, 1, 'theme_set', '_MD_AM_DTHEME', 'impresstheme', '_MD_AM_DTHEMEDSC', 'theme', 'other', 12)");
    $dbm->insert('config', " VALUES (8, 0, 1, 'anonymous', '_MD_AM_ANONNAME', '".addslashes(_INSTALL_ANON)."', '_MD_AM_ANONNAMEDSC', 'textbox', 'text', 15)");
    $dbm->insert('config', " VALUES (9, 0, 1, 'gzip_compression', '_MD_AM_USEGZIP', '0', '_MD_AM_USEGZIPDSC', 'yesno', 'int', 16)");
    $dbm->insert('config', " VALUES (10, 0, 1, 'usercookie', '_MD_AM_USERCOOKIE', 'icms_user', '_MD_AM_USERCOOKIEDSC', 'textbox', 'text', 18)");
    $dbm->insert('config', " VALUES (11, 0, 1, 'session_expire', '_MD_AM_SESSEXPIRE', '15', '_MD_AM_SESSEXPIREDSC', 'textbox', 'int', 22)");
    $dbm->insert('config', " VALUES (12, 0, 1, 'banners', '_MD_AM_BANNERS', '1', '_MD_AM_BANNERSDSC', 'yesno', 'int', 26)");
    $dbm->insert('config', " VALUES (13, 0, 1, 'debug_mode', '_MD_AM_DEBUGMODE', '0', '_MD_AM_DEBUGMODEDSC', 'select', 'int', 24)");
    $dbm->insert('config', " VALUES (14, 0, 1, 'my_ip', '_MD_AM_MYIP', '127.0.0.1', '_MD_AM_MYIPDSC', 'textbox', 'text', 29)");
    $dbm->insert('config', " VALUES (15, 0, 1, 'use_ssl', '_MD_AM_USESSL', '0', '_MD_AM_USESSLDSC', 'yesno', 'int', 30)");
    $dbm->insert('config', " VALUES (16, 0, 1, 'session_name', '_MD_AM_SESSNAME', 'icms_session', '_MD_AM_SESSNAMEDSC', 'textbox', 'text', 20)");
    $dbm->insert('config', " VALUES (17, 0, 2, 'minpass', '_MD_AM_MINPASS', '5', '_MD_AM_MINPASSDSC', 'textbox', 'int', 1)");
    $dbm->insert('config', " VALUES (18, 0, 2, 'minuname', '_MD_AM_MINUNAME', '3', '_MD_AM_MINUNAMEDSC', 'textbox', 'int', 2)");
    $dbm->insert('config', " VALUES (19, 0, 2, 'new_user_notify', '_MD_AM_NEWUNOTIFY', '1', '_MD_AM_NEWUNOTIFYDSC', 'yesno', 'int', 4)");
    $dbm->insert('config', " VALUES (20, 0, 2, 'new_user_notify_group', '_MD_AM_NOTIFYTO', ".$gruops['XOOPS_GROUP_ADMIN'].", '_MD_AM_NOTIFYTODSC', 'group', 'int', 6)");
    $dbm->insert('config', " VALUES (21, 0, 2, 'activation_type', '_MD_AM_ACTVTYPE', '0', '_MD_AM_ACTVTYPEDSC', 'select', 'int', 8)");
    $dbm->insert('config', " VALUES (22, 0, 2, 'activation_group', '_MD_AM_ACTVGROUP', ".$gruops['XOOPS_GROUP_ADMIN'].", '_MD_AM_ACTVGROUPDSC', 'group', 'int', 10)");
    $dbm->insert('config', " VALUES (23, 0, 2, 'uname_test_level', '_MD_AM_UNAMELVL', '0', '_MD_AM_UNAMELVLDSC', 'select', 'int', 12)");
    $dbm->insert('config', " VALUES (24, 0, 2, 'avatar_allow_upload', '_MD_AM_AVATARALLOW', '0', '_MD_AM_AVATARALWDSC', 'yesno', 'int', 14)");
    $dbm->insert('config', " VALUES (27, 0, 2, 'avatar_width', '_MD_AM_AVATARW', '80', '_MD_AM_AVATARWDSC', 'textbox', 'int', 16)");
    $dbm->insert('config', " VALUES (28, 0, 2, 'avatar_height', '_MD_AM_AVATARH', '80', '_MD_AM_AVATARHDSC', 'textbox', 'int', 18)");
    $dbm->insert('config', " VALUES (29, 0, 2, 'avatar_maxsize', '_MD_AM_AVATARMAX', '35000', '_MD_AM_AVATARMAXDSC', 'textbox', 'int', 20)");
    $dbm->insert('config', " VALUES (30, 0, 1, 'adminmail', '_MD_AM_ADMINML', '".addslashes($adminmail)."', '_MD_AM_ADMINMLDSC', 'textbox', 'text', 3)");
    $dbm->insert('config', " VALUES (31, 0, 2, 'self_delete', '_MD_AM_SELFDELETE', '0', '_MD_AM_SELFDELETEDSC', 'yesno', 'int', 22)");
    $dbm->insert('config', " VALUES (32, 0, 1, 'com_mode', '_MD_AM_COMMODE', 'nest', '_MD_AM_COMMODEDSC', 'select', 'text', 34)");
    $dbm->insert('config', " VALUES (33, 0, 1, 'com_order', '_MD_AM_COMORDER', '0', '_MD_AM_COMORDERDSC', 'select', 'int', 36)");
    $dbm->insert('config', " VALUES (34, 0, 2, 'bad_unames', '_MD_AM_BADUNAMES', '".addslashes(serialize(array('webmaster', '^impresscms', '^admin')))."', '_MD_AM_BADUNAMESDSC', 'textarea', 'array', 24)");
    $dbm->insert('config', " VALUES (35, 0, 2, 'bad_emails', '_MD_AM_BADEMAILS', '".addslashes(serialize(array('impresscms.org$')))."', '_MD_AM_BADEMAILSDSC', 'textarea', 'array', 26)");
    $dbm->insert('config', " VALUES (36, 0, 2, 'maxuname', '_MD_AM_MAXUNAME', '10', '_MD_AM_MAXUNAMEDSC', 'textbox', 'int', 3)");
    $dbm->insert('config', " VALUES (37, 0, 1, 'bad_ips', '_MD_AM_BADIPS', '".addslashes(serialize(array('127.0.0.1')))."', '_MD_AM_BADIPSDSC', 'textarea', 'array', 42)");
    $dbm->insert('config', " VALUES (38, 0, 3, 'meta_keywords', '_MD_AM_METAKEY', 'community management system, CMS, content management, social networking, community, blog, support, modules, add-ons, themes', '_MD_AM_METAKEYDSC', 'textarea', 'text', 0)");
	$dbm->insert('config', " VALUES (39, 0, 3, 'footer', '_MD_AM_FOOTER', 'Powered by ImpressCMS 1.0 &copy; 2007-" . date('Y', time()) . " <a href=\"http://www.impresscms.org/\" rel=\"external\">The ImpressCMS Project</a>', '_MD_AM_FOOTERDSC', 'textarea', 'text', 20)");
    $dbm->insert('config', " VALUES (40, 0, 4, 'censor_enable', '_MD_AM_DOCENSOR', '0', '_MD_AM_DOCENSORDSC', 'yesno', 'int', 0)");
    $dbm->insert('config', " VALUES (41, 0, 4, 'censor_words', '_MD_AM_CENSORWRD', '".addslashes(serialize(array('fuck', 'shit', 'cunt', 'wanker', 'bastard')))."', '_MD_AM_CENSORWRDDSC', 'textarea', 'array', 1)");
    $dbm->insert('config', " VALUES (42, 0, 4, 'censor_replace', '_MD_AM_CENSORRPLC', '#OOPS#', '_MD_AM_CENSORRPLCDSC', 'textbox', 'text', 2)");
    $dbm->insert('config', " VALUES (43, 0, 3, 'meta_robots', '_MD_AM_METAROBOTS', 'index,follow', '_MD_AM_METAROBOTSDSC', 'select', 'text', 2)");
    $dbm->insert('config', " VALUES (44, 0, 5, 'enable_search', '_MD_AM_DOSEARCH', '1', '_MD_AM_DOSEARCHDSC', 'yesno', 'int', 0)");
    $dbm->insert('config', " VALUES (45, 0, 5, 'keyword_min', '_MD_AM_MINSEARCH', '5', '_MD_AM_MINSEARCHDSC', 'textbox', 'int', 1)");
    $dbm->insert('config', " VALUES (46, 0, 2, 'avatar_minposts', '_MD_AM_AVATARMP', '0', '_MD_AM_AVATARMPDSC', 'textbox', 'int', 15)");
    $dbm->insert('config', " VALUES (47, 0, 1, 'enable_badips', '_MD_AM_DOBADIPS', '0', '_MD_AM_DOBADIPSDSC', 'yesno', 'int', 40)");
    $dbm->insert('config', " VALUES (48, 0, 3, 'meta_rating', '_MD_AM_METARATING', 'general', '_MD_AM_METARATINGDSC', 'select', 'text', 4)");
    $dbm->insert('config', " VALUES (49, 0, 3, 'meta_author', '_MD_AM_METAAUTHOR', 'ImpressCMS', '_MD_AM_METAAUTHORDSC', 'textbox', 'text', 6)");
    $dbm->insert('config', " VALUES (50, 0, 3, 'meta_copyright', '_MD_AM_METACOPYR', 'Copyright &copy; 2007-" . date('Y', time()) . "', '_MD_AM_METACOPYRDSC', 'textbox', 'text', 8)");
    $dbm->insert('config', " VALUES (51, 0, 3, 'meta_description', '_MD_AM_METADESC', 'ImpressCMS is a dynamic Object Oriented based open source portal script written in PHP.', '_MD_AM_METADESCDSC', 'textarea', 'text', 1)");
    $dbm->insert('config', " VALUES (52, 0, 2, 'allow_chgmail', '_MD_AM_ALLWCHGMAIL', '0', '_MD_AM_ALLWCHGMAILDSC', 'yesno', 'int', 3)");
    $dbm->insert('config', " VALUES (53, 0, 1, 'use_mysession', '_MD_AM_USEMYSESS', '0', '_MD_AM_USEMYSESSDSC', 'yesno', 'int', 19)");
    $dbm->insert('config', " VALUES (54, 0, 2, 'reg_dispdsclmr', '_MD_AM_DSPDSCLMR', 1, '_MD_AM_DSPDSCLMRDSC', 'yesno', 'int', 30)");
    $dbm->insert('config', " VALUES (55, 0, 2, 'reg_disclaimer', '_MD_AM_REGDSCLMR', '".addslashes(_INSTALL_DISCLMR)."', '_MD_AM_REGDSCLMRDSC', 'textarea', 'text', 32)");
    $dbm->insert('config', " VALUES (56, 0, 2, 'allow_register', '_MD_AM_ALLOWREG', 1, '_MD_AM_ALLOWREGDSC', 'yesno', 'int', 0)");
    $dbm->insert('config', " VALUES (57, 0, 1, 'theme_fromfile', '_MD_AM_THEMEFILE', '0', '_MD_AM_THEMEFILEDSC', 'yesno', 'int', 13)");
    $dbm->insert('config', " VALUES (58, 0, 1, 'closesite', '_MD_AM_CLOSESITE', '0', '_MD_AM_CLOSESITEDSC', 'yesno', 'int', 26)");
	$dbm->insert('config', " VALUES (59, 0, 1, 'closesite_okgrp', '_MD_AM_CLOSESITEOK', '".addslashes(serialize(array('1')))."', '_MD_AM_CLOSESITEOKDSC', 'group_multi', 'array', 27)");
	$dbm->insert('config', " VALUES (60, 0, 1, 'closesite_text', '_MD_AM_CLOSESITETXT', '"._INSTALL_L165."', '_MD_AM_CLOSESITETXTDSC', 'textarea', 'text', 28)");
	$dbm->insert('config', " VALUES (61, 0, 1, 'sslpost_name', '_MD_AM_SSLPOST', 'icms_ssl', '_MD_AM_SSLPOSTDSC', 'textbox', 'text', 31)");
	$dbm->insert('config', " VALUES (62, 0, 1, 'module_cache', '_MD_AM_MODCACHE', '', '_MD_AM_MODCACHEDSC', 'module_cache', 'array', 50)");
	$dbm->insert('config', " VALUES (63, 0, 1, 'template_set', '_MD_AM_DTPLSET', 'default', '_MD_AM_DTPLSETDSC', 'tplset', 'other', 14)");

	$dbm->insert('config', " VALUES (64,0,6,'mailmethod','_MD_AM_MAILERMETHOD','mail','_MD_AM_MAILERMETHODDESC','select','text',4)");
	$dbm->insert('config', " VALUES (65,0,6,'smtphost','_MD_AM_SMTPHOST','a:1:{i:0;s:0:\"\";}', '_MD_AM_SMTPHOSTDESC','textarea','array',6)");
	$dbm->insert('config', " VALUES (66,0,6,'smtpuser','_MD_AM_SMTPUSER','','_MD_AM_SMTPUSERDESC','textbox','text',7)");
	$dbm->insert('config', " VALUES (67,0,6,'smtppass','_MD_AM_SMTPPASS','','_MD_AM_SMTPPASSDESC','password','text',8)");
	$dbm->insert('config', " VALUES (68,0,6,'sendmailpath','_MD_AM_SENDMAILPATH','/usr/sbin/sendmail','_MD_AM_SENDMAILPATHDESC','textbox','text',5)");
	$dbm->insert('config', " VALUES (69,0,6,'from','_MD_AM_MAILFROM','','_MD_AM_MAILFROMDESC','textbox','text', 1)");
	$dbm->insert('config', " VALUES (70,0,6,'fromname','_MD_AM_MAILFROMNAME','','_MD_AM_MAILFROMNAMEDESC','textbox','text',2)");
	$dbm->insert('config', " VALUES (71, 0, 1, 'sslloginlink', '_MD_AM_SSLLINK', 'https://', '_MD_AM_SSLLINKDSC', 'textbox', 'text', 33)");
  	$dbm->insert('config', " VALUES (72, 0, 1, 'theme_set_allowed', '_MD_AM_THEMEOK', '".serialize(array('default'))."', '_MD_AM_THEMEOKDSC', 'theme_multi', 'array', 13)");
	// RMV-NOTIFY... Need to specify which user is sender of notification PM
	$dbm->insert('config', " VALUES (73,0,6,'fromuid','_MD_AM_MAILFROMUID','1','_MD_AM_MAILFROMUIDDESC','user','int',3)");

	$dbm->insert('config', " VALUES (74,0,7,'auth_method','_MD_AM_AUTHMETHOD','xoops','_MD_AM_AUTHMETHODDESC','select','text',1)");
	$dbm->insert('config', " VALUES (75,0,7,'ldap_port','_MD_AM_LDAP_PORT','389','_MD_AM_LDAP_PORT','textbox','int',2)");
	$dbm->insert('config', " VALUES (76,0,7,'ldap_server','_MD_AM_LDAP_SERVER','your directory server','_MD_AM_LDAP_SERVER_DESC','textbox','text',3)");
	$dbm->insert('config', " VALUES (77,0,7,'ldap_base_dn','_MD_AM_LDAP_BASE_DN','dc=icms,dc=org','_MD_AM_LDAP_BASE_DN_DESC','textbox','text',4)");
	$dbm->insert('config', " VALUES (78,0,7,'ldap_manager_dn','_MD_AM_LDAP_MANAGER_DN','manager_dn','_MD_AM_LDAP_MANAGER_DN_DESC','textbox','text',5)");
	$dbm->insert('config', " VALUES (79,0,7,'ldap_manager_pass','_MD_AM_LDAP_MANAGER_PASS','manager_pass','_MD_AM_LDAP_MANAGER_PASS_DESC','password','text',6)");
	$dbm->insert('config', " VALUES (80,0,7,'ldap_version','_MD_AM_LDAP_VERSION','3','_MD_AM_LDAP_VERSION_DESC','textbox','text', 7)");
	$dbm->insert('config', " VALUES (81,0,7,'ldap_users_bypass','_MD_AM_LDAP_USERS_BYPASS','".serialize(array('admin'))."','_MD_AM_LDAP_USERS_BYPASS_DESC','textarea','array',8)");
	$dbm->insert('config', " VALUES (82,0,7,'ldap_loginname_asdn','_MD_AM_LDAP_LOGINNAME_ASDN','uid_asdn','_MD_AM_LDAP_LOGINNAME_ASDN_D','yesno','int',9)");
	$dbm->insert('config', " VALUES (83,0,7,'ldap_loginldap_attr', '_MD_AM_LDAP_LOGINLDAP_ATTR', 'uid', '_MD_AM_LDAP_LOGINLDAP_ATTR_D', 'textbox', 'text', 10)");
	$dbm->insert('config', " VALUES (84,0,7,'ldap_filter_person','_MD_AM_LDAP_FILTER_PERSON','','_MD_AM_LDAP_FILTER_PERSON_DESC','textbox','text',11)");
	$dbm->insert('config', " VALUES (85,0,7,'ldap_domain_name','_MD_AM_LDAP_DOMAIN_NAME','mydomain','_MD_AM_LDAP_DOMAIN_NAME_DESC','textbox','text',12)");
	$dbm->insert('config', " VALUES (86,0,7,'ldap_provisionning','_MD_AM_LDAP_PROVIS','0','_MD_AM_LDAP_PROVIS_DESC','yesno','int',13)");
	$dbm->insert('config', " VALUES (87,0,7,'ldap_provisionning_group','_MD_AM_LDAP_PROVIS_GROUP','a:1:{i:0;s:1:\"2\";}','_MD_AM_LDAP_PROVIS_GROUP_DSC','group_multi','array',14)");

	$dbm->insert('config', " VALUES (88,0,7,'ldap_mail_attr','_MD_AM_LDAP_MAIL_ATTR','mail','_MD_AM_LDAP_MAIL_ATTR_DESC','textbox','text',15)");
	$dbm->insert('config', " VALUES (89,0,7,'ldap_givenname_attr','_MD_AM_LDAP_GIVENNAME_ATTR','givenname','_MD_AM_LDAP_GIVENNAME_ATTR_DSC','textbox','text',16)");
	$dbm->insert('config', " VALUES (90,0,7,'ldap_surname_attr','_MD_AM_LDAP_SURNAME_ATTR','sn','_MD_AM_LDAP_SURNAME_ATTR_DESC','textbox','text',17)");
	$dbm->insert('config', " VALUES (91,0,7,'ldap_field_mapping','_MD_AM_LDAP_FIELD_MAPPING_ATTR','email=mail|name=displayname','_MD_AM_LDAP_FIELD_MAPPING_DESC','textarea','text',18)");
	$dbm->insert('config', " VALUES (92,0,7,'ldap_provisionning_upd', '_MD_AM_LDAP_PROVIS_UPD', '1', '_MD_AM_LDAP_PROVIS_UPD_DESC', 'yesno', 'int', 19)");
	$dbm->insert('config', " VALUES (93,0,7,'ldap_use_TLS','_MD_AM_LDAP_USETLS','0','_MD_AM_LDAP_USETLS_DESC','yesno','int', 20)");

    $dbm->insert('config', " VALUES (94, 0, 2, 'rank_width', '_MD_AM_RANKW', '120', '_MD_AM_RANKWDSC', 'textbox', 'int', 21)");
    $dbm->insert('config', " VALUES (95, 0, 2, 'rank_height', '_MD_AM_RANKH', '120', '_MD_AM_RANKHDSC', 'textbox', 'int', 21)");
    $dbm->insert('config', " VALUES (96, 0, 2, 'rank_maxsize', '_MD_AM_RANKMAX', '35000', '_MD_AM_RANKMAXDSC', 'textbox', 'int', 21)");

	$dbm->insert('config', " VALUES (97, 0, 8,'ml_enable', '_MD_AM_ML_ENABLE', '0', '_MD_AM_ML_ENABLEDEC', 'yesno', 'int', 0)");
	$dbm->insert('config', " VALUES (98, 0, 8,'ml_tags', '_MD_AM_ML_TAGS', 'en,fr', '_MD_AM_ML_TAGSDSC', 'textbox', 'text', 5)");
	$dbm->insert('config', " VALUES (99, 0, 8,'ml_names', '_MD_AM_ML_NAMES', 'english,french', '_MD_AM_ML_NAMESDSC', 'textbox', 'text', 10)");
	$dbm->insert('config', " VALUES (100, 0, 8,'ml_captions', '_MD_AM_ML_CAPTIONS', 'English,Francais', '_MD_AM_ML_CAPTIONSDSC', 'textbox', 'text', 15)");
	$dbm->insert('config', " VALUES (101, 0, 8,'ml_charset', '_MD_AM_ML_CHARSET', 'UTF-8,ISO-8859-15', '_MD_AM_ML_CHARSETDSC', 'textbox', 'text', 16)");

	$dbm->insert('config', " VALUES (102, 0, 2, 'remember_me', '_MD_AM_REMEMBERME', '0', '_MD_AM_REMEMBERMEDSC', 'yesno', 'int', 29)");
    $dbm->insert('config', " VALUES (103, 0, 2, 'priv_dpolicy', '_MD_AM_PRIVDPOLICY', 0, '_MD_AM_PRIVDPOLICYDSC', 'yesno', 'int', 33)");
    $dbm->insert('config', " VALUES (104, 0, 2, 'priv_policy', '_MD_AM_PRIVPOLICY', '".addslashes(_INSTALL_PRIVPOLICY)."', '_MD_AM_PRIVPOLICYDSC', 'textarea', 'text', 34)");

	$dbm->insert('config', " VALUES (105, 0, 2, 'ml_autoselect_enabled', '_MD_AM_ML_AUTOSELECT_ENABLED', '0', '_MD_AM_ML_AUTOSELECT_ENABLED_DESC', 'yesno', 'text', 2)");
	
	$dbm->insert('config', " VALUES (106, 0, 1, 'editor_default', '_MD_AM_EDITOR_DEFAULT', 'default', '_MD_AM_EDITOR_DEFAULT_DESC', 'editor', 'text', 14)");
	$dbm->insert('config', " VALUES (107, 0, 1, 'editor_enabled_list', '_MD_AM_EDITOR_ENABLED_LIST', '".addslashes(serialize(array('default')))."', '_MD_AM_EDITOR_ENABLED_LIST_DESC', 'editor_multi', 'array', 14)");
	$dbm->insert('config', " VALUES (108, 0, 2, 'allow_annon_view_prof', '_MD_AM_ALLOW_ANONYMOUS_VIEW_PROFILE', '0', '_MD_AM_ALLOW_ANONYMOUS_VIEW_PROFILE_DESC', 'yesno', 'int', 50)");

    return $gruops;
}



?>
