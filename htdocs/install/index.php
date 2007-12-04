<?php
// $Id: index.php 1083 2007-10-16 16:42:51Z phppp $
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

error_reporting (0);

include_once './passwd.php';
if(INSTALL_USER != '' || INSTALL_PASSWD != ''){
    if (!isset($_SERVER['PHP_AUTH_USER'])) {
        header('WWW-Authenticate: Basic realm="XOOPS Installer"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'You can not access this XOOPS installer.';
        exit;
    } else {
        if(INSTALL_USER != '' && $_SERVER['PHP_AUTH_USER'] != INSTALL_USER){
            header('HTTP/1.0 401 Unauthorized');
            echo 'You can not access this XOOPS installer.';
            exit;
        }
        if(INSTALL_PASSWD != $_SERVER['PHP_AUTH_PW']){
            header('HTTP/1.0 401 Unauthorized');
            echo 'You can not access this XOOPS installer.';
            exit;
        }
    }
}

include_once './class/textsanitizer.php';
$myts =& TextSanitizer::getInstance();

if ( isset($_POST) ) {
    foreach ($_POST as $k=>$v) {
        if (!is_array($v)) {
            $$k = $myts->stripSlashesGPC($v);
        }
    }
}

$language = 'english';
if ( !empty($_POST['lang']) ) {
    $language = $_POST['lang'];
} else {
	if (isset($_COOKIE['install_lang'])) {
		$language = $_COOKIE['install_lang'];
	} else {
		//$_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'ja,en-us;q=0.7,zh-TW;q=0.6';
		if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
			$accept_langs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
			$language_array = array('en' => 'english', 'ja' => 'japanese', 'fr' => 'french', 'de' => 'german', 'nl' => 'dutch', 'es' => 'spanish', 'tw' => 'tchinese', 'cn' => 'schinese', 'ro' => 'romanian');
			foreach ($accept_langs as $al) {
				$al = strtolower($al);
				$al_len = strlen($al);
				if ($al_len > 2) {
					if (preg_match("/([a-z]{2});q=[0-9.]+$/", $al, $al_match)) {
						$al = $al_match[1];
					} else {
						continue;
					}
				}
				if (isset($language_array[$al])) {
					$language = $language_array[$al];
					break;
				}
			}
		}
	}
}

if ( file_exists("./language/".$language."/install.php") ) {
    include_once "./language/".$language."/install.php";
} elseif ( file_exists("./language/english/install.php") ) {
    include_once "./language/english/install.php";
    $language = 'english';
} else {
    echo 'no language file.';
    exit();
}
setcookie("install_lang", $language);

//include './include/viewerrors.php';
//include './include/functions.php';

define('_OKIMG',"<img src='img/yes.gif' width='6' height='12' border='0' alt='' /> ");
define('_NGIMG',"<img src='img/no.gif' width='6' height='12' border='0' alt='' /> ");

$b_back = '';
$b_reload = '';
$b_next = '';

// options for mainfile.php
$xoopsOption['nocommon'] = true;
define('XOOPS_INSTALL', 1);

if(!empty($_POST['op']))
    $op = $_POST['op'];
elseif(!empty($_GET['op']))
    $op = $_GET['op'];
else
    $op = '';

///// main
switch ($op) {

default:
case "langselect":
    $title = _INSTALL_L0;
	if (!defined('_INSTALL_L128')) {
		define('_INSTALL_L128', 'Choose language to be used for the installation process');
	}
    $content = "<p>"._INSTALL_L128."</p>"
              ."<select name='lang'>";

    $langarr = getDirList("./language/");
    foreach ($langarr as $lang) {
        $content .= "<option value='".$lang."'";
        if (strtolower($lang) == $language) {
            $content .= ' selected="selected"';
        }
        $content .= ">".$lang."</option>";
    }
    $content .= "</select>";

    $b_next = array('start', _INSTALL_L80 );
    include 'install_tpl.php';
    break;

case "start":
    $title = _INSTALL_L0;
    $content = "<table width='80%' align='center'><tr><td align='left'>\n";
    include './language/'.$language.'/welcome.php';
    $content .= "</td></tr></table>\n";
    $b_next = array('modcheck', _INSTALL_L81 );
    include 'install_tpl.php';
    break;

case "modcheck":
    $writeok = array("uploads/", "cache/", "templates_c/", "mainfile.php");
    $title = _INSTALL_L82;
    $content = "<table align='center'><tr><td align='left'>\n";
    $error = false;
    foreach ($writeok as $wok) {
        if (!is_dir("../".$wok)) {
            if ( file_exists("../".$wok) ) {
                @chmod("../".$wok, 0666);
                if (! is_writeable("../".$wok)) {
                    $content .= _NGIMG.sprintf(_INSTALL_L83, $wok)."<br />";
                    $error = true;
                }else{
                    $content .= _OKIMG.sprintf(_INSTALL_L84, $wok)."<br />";
                }
            }
        } else {
            @chmod("../".$wok, 0777);
            if (! is_writeable("../".$wok)) {
                $content .= _NGIMG.sprintf(_INSTALL_L85, $wok)."<br />";
                $error = true;
            }else{
                $content .= _OKIMG.sprintf(_INSTALL_L86, $wok)."<br />";
            }
        }
    }
    $content .= "</td></tr></table>\n";

    if(! $error) {
        $content .= "<p>"._INSTALL_L87."</p>";
        $b_next = array('dbform', _INSTALL_L89 );
    }else{
        $content .= "<p>"._INSTALL_L46."</p>";
        $b_reload = true;
    }

    include 'install_tpl.php';
    break;

case "dbform":
    include_once '../mainfile.php';
    include_once 'class/settingmanager.php';
    $sm = new setting_manager();
    $sm->readConstant();
    $content = $sm->editform();
    $title = _INSTALL_L90;
    $b_next = array('dbconfirm',_INSTALL_L91);
    include 'install_tpl.php';
    break;

case "dbconfirm":
    include_once 'class/settingmanager.php';
    $sm = new setting_manager(true);

    $content = $sm->checkData();
    if (!empty($content)) {
        $content .= $sm->editform();
        $b_next = array('dbconfirm',_INSTALL_L91);
        include 'install_tpl.php';
        break;
    }

    $title = _INSTALL_L53;
    $content = $sm->confirmForm();
    $b_next = array('dbsave',_INSTALL_L92 );
    $b_back = array('', _INSTALL_L93 );
    include 'install_tpl.php';
    break;

case "dbsave":
    include_once "./class/mainfilemanager.php";
    $title = _INSTALL_L88;
    $mm = new mainfile_manager("../mainfile.php");

    $ret = $mm->copyDistFile();
    if(! $ret){
        $content = _INSTALL_L60;
        include 'install_tpl.php';
        exit();
    }

    $mm->setRewrite('XOOPS_ROOT_PATH', trim($myts->stripSlashesGPC($_POST['root_path'])));
    $mm->setRewrite('XOOPS_URL', trim($myts->stripSlashesGPC($_POST['xoops_url'])));
    $mm->setRewrite('XOOPS_DB_TYPE', trim($myts->stripSlashesGPC($_POST['database'])));
    $mm->setRewrite('XOOPS_DB_PREFIX', trim($myts->stripSlashesGPC($_POST['prefix'])));
    $mm->setRewrite('XOOPS_DB_HOST', trim($myts->stripSlashesGPC($_POST['dbhost'])));
    $mm->setRewrite('XOOPS_DB_USER', trim($myts->stripSlashesGPC($_POST['dbuname'])));
    $mm->setRewrite('XOOPS_DB_PASS', trim($myts->stripSlashesGPC($_POST['dbpass'])));
    $mm->setRewrite('XOOPS_DB_NAME', trim($myts->stripSlashesGPC($_POST['dbname'])));
    $mm->setRewrite('XOOPS_DB_PCONNECT', intval($_POST['db_pconnect']));
    $mm->setRewrite('XOOPS_GROUP_ADMIN', 1);
    $mm->setRewrite('XOOPS_GROUP_USERS', 2);
    $mm->setRewrite('XOOPS_GROUP_ANONYMOUS', 3);

	// Check if XOOPS_CHECK_PATH should be initially set or not
	$xoopsPathTrans = isset($_SERVER['PATH_TRANSLATED']) ? $_SERVER['PATH_TRANSLATED'] :  $_SERVER['SCRIPT_FILENAME'];
	if ( DIRECTORY_SEPARATOR != '/' ) {
	 	// IIS6 doubles the \ chars
		$xoopsPathTrans = str_replace( strpos( $xoopsPathTrans, '\\\\', 2 ) ? '\\\\' : DIRECTORY_SEPARATOR, '/', $xoopsPathTrans);
	}
	$mm->setRewrite('XOOPS_CHECK_PATH', strcasecmp( substr($xoopsPathTrans, 0, strlen($myts->stripSlashesGPC($_POST['root_path']))), $_POST['root_path']) ? 0 : 1 );

    $ret = $mm->doRewrite();
    if(! $ret){
        $content = _INSTALL_L60;
        include 'install_tpl.php';
        exit();
    }

    $content = $mm->report();
    $content .= "<p>"._INSTALL_L62."</p>\n";
    $b_next = array('mainfile', _INSTALL_L94 );
    include 'install_tpl.php';
    break;

case "mainfile":
    // checking XOOPS_ROOT_PATH and XOOPS_URL
    include_once "../mainfile.php";
    $title = _INSTALL_L94;
    $content = "<table align='center'><tr><td align='left'>\n";

    $detected = str_replace("\\", "/", getcwd()); // "
    $detected = str_replace("/install", "", $detected);
    if ( substr($detected, -1) == "/" ) {
        $detected = substr($detected, 0, -1);
    }

    if (empty($detected)){
        $content .= _NGIMG._INSTALL_L95.'<br />';
    }
    elseif ( XOOPS_ROOT_PATH != $detected ) {
        $content .= _NGIMG.sprintf(_INSTALL_L96,$detected). '<br />';
    }else {
        $content .= _OKIMG._INSTALL_L97.'<br />';
    }

    if(!is_dir(XOOPS_ROOT_PATH)){
        $content .= _NGIMG._INSTALL_L99.'<br />';
    }

    if(preg_match('/^http[s]?:\/\/(.*)[^\/]+$/i',XOOPS_URL)){
        $content .= _OKIMG._INSTALL_L100.'<br />';
    }else{
        $content .= _NGIMG._INSTALL_L101.'<br />';
    }

    $content .= "<br /></td></tr></table>\n";

    $content .= "<table align='center'><tr><td align='left'>\n";
    $content .= _INSTALL_L11."<b>".XOOPS_ROOT_PATH."</b><br />";
    $content .= _INSTALL_L12."<b>".XOOPS_URL."</b><br />";
    $content .= "</td></tr></table>\n";
    $content .= "<p align='center'>"._INSTALL_L13."</p>\n";

    $b_next = array('initial', _INSTALL_L102 );
    $b_back = array('start', _INSTALL_L103 );
    $b_reload = true;

    include 'install_tpl.php';
    //mainfile_settings();
    break;

case "initial":
    // confirm database setting
    include_once "../mainfile.php";
    $content = "<table align=\"center\">\n";
    $content .= "<tr><td align='center'>";
    $content .= "<table align=\"center\">\n";
    $content .= "<tr><td>"._INSTALL_L27."&nbsp;&nbsp;</td><td><b>".XOOPS_DB_HOST."</b></td></tr>\n";
    $content .= "<tr><td>"._INSTALL_L28."&nbsp;&nbsp;</td><td><b>".XOOPS_DB_USER."</b></td></tr>\n";
    $content .= "<tr><td>"._INSTALL_L29."&nbsp;&nbsp;</td><td><b>".XOOPS_DB_NAME."</b></td></tr>\n";
    $content .= "<tr><td>"._INSTALL_L30."&nbsp;&nbsp;</td><td><b>".XOOPS_DB_PREFIX."</b></td></tr>\n";
    $content .= "</table><br />\n";
    $content .= "</td></tr><tr><td align=\"center\">";
    $content .= _INSTALL_L13."<br /><br />\n";
    $content .= "</td></tr></table>\n";
    $b_next = array('checkDB', _INSTALL_L104);
    $b_back = array('start', _INSTALL_L103);
    $b_reload = true;
    $title = _INSTALL_L102;
    include 'install_tpl.php';
    break;

case "checkDB":
    include_once "../mainfile.php";
    include_once './class/dbmanager.php';
    $dbm = new db_manager;
    $title = _INSTALL_L104;
    $content = "<table align='center'><tr><td align='left'>\n";

    if (! $dbm->isConnectable()) {
        $content .= _NGIMG._INSTALL_L106."<br />";
        $content .= "<div style='text-align:center'><br />"._INSTALL_L107;
        $content .= "</div></td></tr></table>\n";
        $b_back = array('start', _INSTALL_L103);
        $b_reload = true;
    }else{
        $content .= _OKIMG._INSTALL_L108."<br />";
        if (! $dbm->dbExists()) {
            $content .= _NGIMG.sprintf(_INSTALL_L109, XOOPS_DB_NAME)."<br />";
            $content .= "</td></tr></table>\n";

            $content .= "<p>"._INSTALL_L21."<br />"
                        ."<b>".XOOPS_DB_NAME."</b></p>"
                        ."<p>"._INSTALL_L22."</p>";

            $b_next = array('createDB', _INSTALL_L105);
            $b_back = array('start', _INSTALL_L103);
            $b_reload = true;
        }else{
			if (!$dbm->tableExists('users')) {
            	$content .= _OKIMG.sprintf(_INSTALL_L110, XOOPS_DB_NAME)."<br />";
            	$content .= "</td></tr></table>\n";
            	$content .= "<p>"._INSTALL_L111."</p>";
            	$b_next = array('createTables', _INSTALL_L40);
			} else {
				$content .= _OKIMG.sprintf(_INSTALL_L110, XOOPS_DB_NAME)."<br />";
				if (!$dbm->tableExists('config')) {
            		$content .= "</td></tr></table>\n";
            		$content .= "<p>"._INSTALL_L130."</p>";
            		$b_next = array('updateTables', _INSTALL_L14);
				} else {
					$content .= _NGIMG._INSTALL_L131."<br />";
					$content .= "</td></tr></table>\n";
            	}
			}
        }
    }

    include 'install_tpl.php';
    break;

case "createDB":
    include_once "../mainfile.php";
    include_once './class/dbmanager.php';
    $dbm = new db_manager;

    if(! $dbm->createDB()){
        $content = "<p>"._INSTALL_L31."</p>";
        $b_next = array('checkDB', _INSTALL_L104);
        $b_back = array('start', _INSTALL_L103);
    }else{
        $content = "<p>".sprintf(_INSTALL_L43, XOOPS_DB_NAME)."</p>";
        $b_next = array('checkDB', _INSTALL_L104);
    }
    include 'install_tpl.php';
    break;

case "createTables":
    include_once "../mainfile.php";
    include_once './class/dbmanager.php';
    $dbm = new db_manager;

    //$content = "<table align='center'><tr><td align='left'>\n";
    $tables = array();
    $result = $dbm->queryFromFile('./sql/'.XOOPS_DB_TYPE.'.structure.sql');
    $content = $dbm->report();
    if(! $result ){
        //$deleted = $dbm->deleteTables($tables);
        $content .= "<p>"._INSTALL_L114."</p>\n";
        $b_back = array('start', _INSTALL_L103);
    }else{
        $content .= "<p>"._INSTALL_L115."</p>\n";
        $b_next = array('siteInit', _INSTALL_L112);
    }

    include 'install_tpl.php';
    break;

case 'updateTables':
	include_once "../mainfile.php";
	include_once './class/dbmanager.php';
    $db = new db_manager;
	$sql = 'SELECT * FROM '.$db->prefix('groups');
	$result = $db->query($sql);
	$content = '<h5>'._INSTALL_L157.'</h5>';
	$content .= '<table align="center" cellspacing="0" border="1"><tr><td>'._INSTALL_L158.'</td><td>'._INSTALL_L159.'</td><td>'._INSTALL_L160.'</td><td>'._INSTALL_L161.'</td></tr>';
	while ($myrow = $db->fetchArray($result)) {
		if ($myrow['type'] == 'Admin') {
			$content .= '<tr><td>'.$myrow['name'].'</td><td><input type="radio" name="g_webmasters" value="'.$myrow['groupid'].'" /></td><td>&nbsp;</td><td>&nbsp;</td></tr>';
		} elseif ($myrow['type'] == 'User') {
			$content .= '<tr><td>'.$myrow['name'].'</td><td>&nbsp;</td><td><input type="radio" name="g_users" value="'.$myrow['groupid'].'" /></td><td>&nbsp;</td></tr>';
		} else {
			$content .= '<tr><td>'.$myrow['name'].'</td><td>&nbsp;</td><td>&nbsp;</td><td><input type="radio" name="g_anonymous" value="'.$myrow['groupid'].'" /></td></tr>';
		}
	}
	$content .= '</table>';
	$b_back = array();
	$b_next = array('updateTables_go', _INSTALL_L132);
	include 'install_tpl.php';
	break;

case 'updateTables_go':
	include_once "../mainfile.php";
	$error = false;
	$g_webmasters = isset($g_webmasters) ? intval($g_webmasters) : 0;
	$g_users = isset($g_users) ? intval($g_users) : 0;
	$g_anonymous = isset($g_anonymous) ? intval($g_anonymous) : 0;
	if (empty($g_webmasters) || empty($g_users) || empty($g_anonymous)) {
		$error = true;
	} else {
		include_once "./class/mainfilemanager.php";
    	$title = _INSTALL_L88;
    	$mm = new mainfile_manager("../mainfile.php");
    	$mm->setRewrite('XOOPS_GROUP_ADMIN', $g_webmasters);
    	$mm->setRewrite('XOOPS_GROUP_USERS', $g_users);
    	$mm->setRewrite('XOOPS_GROUP_ANONYMOUS', $g_anonymous);

    	$ret = $mm->doRewrite();
    	if(!$ret){
        	$content = _INSTALL_L60;
        	include 'install_tpl.php';
        	exit();
    	}
	}
	if (false != $error) {
		$b_back = array();
		$content = _INSTALL_L162;
		include 'install_tpl.php';
		break;
	}
	include_once './class/dbmanager.php';
    $dbm = new db_manager;
    if (!$dbm->query("ALTER TABLE ".$dbm->prefix("newblocks")." ADD dirname VARCHAR(50) NOT NULL, ADD func_file VARCHAR(50) NOT NULL, ADD show_func VARCHAR(50) NOT NULL, ADD edit_func VARCHAR(50) NOT NULL")) {
    }
    $result = $dbm->queryFromFile('./sql/upgrade/'.XOOPS_DB_TYPE.'.structure.sql');
    $content = $dbm->report();
    if (!$result) {
        $content .= "<p>"._INSTALL_L135."</p>\n";
		$b_back = array();
    } else {
        $content .= "<p>"._INSTALL_L136."</p>\n";
		$b_next = array('updateConfig', _INSTALL_L14);
    }
	include 'install_tpl.php';
	break;

case 'updateConfig':
	$b_next = array('updateConfig_go', _INSTALL_L144);
	$content = "<p>"._INSTALL_L143."</p>\n";
	include 'install_tpl.php';
	break;

case 'updateConfig_go':
	include_once "../mainfile.php";

	$language = check_language($language);
    if ( file_exists("./language/".$language."/install2.php") ) {
        include_once "./language/".$language."/install2.php";
    } elseif ( file_exists("./language/english/install2.php") ) {
        include_once "./language/english/install2.php";
        $language = 'english';
    } else {
        echo 'no language file (install2.php).';
        exit();
    }
	include_once './class/dbmanager.php';
    $dbm = new db_manager;

	// default settings
	$xoopsConfig['sitename'] = 'XOOPS Site';
	$xoopsConfig['slogan'] = 'Just use it!';
	$xoopsConfig['adminmail'] = '';
	$xoopsConfig['language'] = 'english';
	$xoopsConfig['anonymous'] = 'Anonymous';
	$xoopsConfig['minpass'] = 5;
	$xoopsConfig['anonpost'] = 0;
	$xoopsConfig['new_user_notify'] = 0;
	$xoopsConfig['new_user_notify_group'] = 1;
	$xoopsConfig['self_delete'] = 0;
	$xoopsConfig['gzip_compression'] = 0;
	$xoopsConfig['uname_test_level'] = 0;
	$xoopsConfig['usercookie'] = "xoops_user";
	$xoopsConfig['sessioncookie'] = "xoops_session";
	$xoopsConfig['sessionexpire'] = 4500;
	$xoopsConfig['server_TZ'] = 0;
	$xoopsConfig['default_TZ'] = 0;
	$xoopsConfig['banners'] = 1;
	$xoopsConfig['com_mode'] = "nest";
	$xoopsConfig['com_order'] = 1;
	$xoopsConfig['my_ip'] = "127.0.0.1";
	$xoopsConfig['avatar_allow_upload'] = 0;
	$xoopsConfig['avatar_width'] = 120;
	$xoopsConfig['avatar_height'] = 120;
	$xoopsConfig['avatar_maxsize'] = 15000;

	// override deafault with 1.3.x settings if any
	if (file_exists('../modules/system/cache/config.php')) {
		include_once('../modules/system/cache/config.php');
	}

	$dbm->insert('config', " VALUES (1, 0, 1, 'sitename', '_MD_AM_SITENAME', '".addslashes($xoopsConfig['sitename'])."', '_MD_AM_SITENAMEDSC', 'textbox', 'text', 0)");
	$dbm->insert('config', " VALUES (2, 0, 1, 'slogan', '_MD_AM_SLOGAN', '".addslashes($xoopsConfig['slogan'])."', '_MD_AM_SLOGANDSC', 'textbox', 'text', 2)");
	$dbm->insert('config', " VALUES (3, 0, 1, 'language', '_MD_AM_LANGUAGE', '".$xoopsConfig['language']."', '_MD_AM_LANGUAGEDSC', 'language', 'other', 4)");
	$dbm->insert('config', " VALUES (4, 0, 1, 'startpage', '_MD_AM_STARTPAGE', '--', '_MD_AM_STARTPAGEDSC', 'startpage', 'other', 6)");
	$dbm->insert('config', " VALUES (5, 0, 1, 'server_TZ', '_MD_AM_SERVERTZ', '".addslashes($xoopsConfig['server_TZ'])."', '_MD_AM_SERVERTZDSC', 'timezone', 'float', 8)");
	$dbm->insert('config', " VALUES (6, 0, 1, 'default_TZ', '_MD_AM_DEFAULTTZ', '".addslashes($xoopsConfig['default_TZ'])."', '_MD_AM_DEFAULTTZDSC', 'timezone', 'float', 10)");
	$dbm->insert('config', " VALUES (7, 0, 1, 'theme_set', '_MD_AM_DTHEME', 'default', '_MD_AM_DTHEMEDSC', 'theme', 'other', 12)");
	$dbm->insert('config', " VALUES (8, 0, 1, 'anonymous', '_MD_AM_ANONNAME', '".addslashes($xoopsConfig['anonymous'])."', '_MD_AM_ANONNAMEDSC', 'textbox', 'text', 15)");
	$dbm->insert('config', " VALUES (9, 0, 1, 'gzip_compression', '_MD_AM_USEGZIP', '".intval($xoopsConfig['gzip_compression'])."', '_MD_AM_USEGZIPDSC', 'yesno', 'int', 16)");
	$dbm->insert('config', " VALUES (10, 0, 1, 'usercookie', '_MD_AM_USERCOOKIE', '".addslashes($xoopsConfig['usercookie'])."', '_MD_AM_USERCOOKIEDSC', 'textbox', 'text', 18)");
    $dbm->insert('config', " VALUES (11, 0, 1, 'session_expire', '_MD_AM_SESSEXPIRE', '15', '_MD_AM_SESSEXPIREDSC', 'textbox', 'int', 22)");
	$dbm->insert('config', " VALUES (12, 0, 1, 'banners', '_MD_AM_BANNERS', '".intval($xoopsConfig['banners'])."', '_MD_AM_BANNERSDSC', 'yesno', 'int', 26)");
	$dbm->insert('config', " VALUES (13, 0, 1, 'debug_mode', '_MD_AM_DEBUGMODE', '0', '_MD_AM_DEBUGMODEDSC', 'select', 'int', 24)");
	$dbm->insert('config', " VALUES (14, 0, 1, 'my_ip', '_MD_AM_MYIP', '".addslashes($xoopsConfig['my_ip'])."', '_MD_AM_MYIPDSC', 'textbox', 'text', 29)");
	$dbm->insert('config', " VALUES (15, 0, 1, 'use_ssl', '_MD_AM_USESSL', '0', '_MD_AM_USESSLDSC', 'yesno', 'int', 30)");
    $dbm->insert('config', " VALUES (16, 0, 1, 'session_name', '_MD_AM_SESSNAME', 'xoops_session', '_MD_AM_SESSNAMEDSC', 'textbox', 'text', 20)");
	$dbm->insert('config', " VALUES (17, 0, 2, 'minpass', '_MD_AM_MINPASS', '".intval($xoopsConfig['minpass'])."', '_MD_AM_MINPASSDSC', 'textbox', 'int', 1)");
	$dbm->insert('config', " VALUES (18, 0, 2, 'minuname', '_MD_AM_MINUNAME', '5', '_MD_AM_MINUNAMEDSC', 'textbox', 'int', 2)");
	$dbm->insert('config', " VALUES (19, 0, 2, 'new_user_notify', '_MD_AM_NEWUNOTIFY', '".intval($xoopsConfig['new_user_notify'])."', '_MD_AM_NEWUNOTIFYDSC', 'yesno', 'int', 4)");
	$dbm->insert('config', " VALUES (20, 0, 2, 'new_user_notify_group', '_MD_AM_NOTIFYTO', ".intval($xoopsConfig['new_user_notify_group']).", '_MD_AM_NOTIFYTODSC', 'group', 'int', 6)");
	$dbm->insert('config', " VALUES (21, 0, 2, 'activation_type', '_MD_AM_ACTVTYPE', '0', '_MD_AM_ACTVTYPEDSC', 'select', 'int', 8)");
	$dbm->insert('config', " VALUES (22, 0, 2, 'activation_group', '_MD_AM_ACTVGROUP', ".XOOPS_GROUP_ADMIN.", '_MD_AM_ACTVGROUPDSC', 'group', 'int', 10)");
	$dbm->insert('config', " VALUES (23, 0, 2, 'uname_test_level', '_MD_AM_UNAMELVL', '".intval($xoopsConfig['uname_test_level'])."', '_MD_AM_UNAMELVLDSC', 'select', 'int', 12)");
	$dbm->insert('config', " VALUES (24, 0, 2, 'avatar_allow_upload', '_MD_AM_AVATARALLOW', '".intval($xoopsConfig['avatar_allow_upload'])."', '_MD_AM_AVATARALWDSC', 'yesno', 'int', 14)");
	$dbm->insert('config', " VALUES (27, 0, 2, 'avatar_width', '_MD_AM_AVATARW', '".intval($xoopsConfig['avatar_width'])."', '_MD_AM_AVATARWDSC', 'textbox', 'int', 16)");
	$dbm->insert('config', " VALUES (28, 0, 2, 'avatar_height', '_MD_AM_AVATARH', '".intval($xoopsConfig['avatar_height'])."', '_MD_AM_AVATARHDSC', 'textbox', 'int', 18)");
	$dbm->insert('config', " VALUES (29, 0, 2, 'avatar_maxsize', '_MD_AM_AVATARMAX', '".intval($xoopsConfig['avatar_maxsize'])."', '_MD_AM_AVATARMAXDSC', 'textbox', 'int', 20)");
	$dbm->insert('config', " VALUES (30, 0, 1, 'adminmail', '_MD_AM_ADMINML', '".addslashes($xoopsConfig['adminmail'])."', '_MD_AM_ADMINMLDSC', 'textbox', 'text', 3)");
	$dbm->insert('config', " VALUES (31, 0, 2, 'self_delete', '_MD_AM_SELFDELETE', '".intval($xoopsConfig['self_delete'])."', '_MD_AM_SELFDELETEDSC', 'yesno', 'int', 22)");
	$dbm->insert('config', " VALUES (32, 0, 1, 'com_mode', '_MD_AM_COMMODE', '".addslashes($xoopsConfig['com_mode'])."', '_MD_AM_COMMODEDSC', 'select', 'text', 34)");
	$dbm->insert('config', " VALUES (33, 0, 1, 'com_order', '_MD_AM_COMORDER', '".intval($xoopsConfig['com_order'])."', '_MD_AM_COMORDERDSC', 'select', 'int', 36)");
	$dbm->insert('config', " VALUES (34, 0, 2, 'bad_unames', '_MD_AM_BADUNAMES', '".addslashes(serialize(array('webmaster', '^xoops', '^admin')))."', '_MD_AM_BADUNAMESDSC', 'textarea', 'array', 24)");
	$dbm->insert('config', " VALUES (35, 0, 2, 'bad_emails', '_MD_AM_BADEMAILS', '".addslashes(serialize(array('xoops.org$')))."', '_MD_AM_BADEMAILSDSC', 'textarea', 'array', 26)");
	$dbm->insert('config', " VALUES (36, 0, 2, 'maxuname', '_MD_AM_MAXUNAME', '10', '_MD_AM_MAXUNAMEDSC', 'textbox', 'int', 3)");
	$dbm->insert('config', " VALUES (37, 0, 1, 'bad_ips', '_MD_AM_BADIPS', '".addslashes(serialize(array('127.0.0.1')))."', '_MD_AM_BADIPSDSC', 'textarea', 'array', 42)");
	$dbm->insert('config', " VALUES (38, 0, 3, 'meta_keywords', '_MD_AM_METAKEY', 'news, technology, headlines, xoops, xoop, nuke, myphpnuke, myphp-nuke, phpnuke, SE, geek, geeks, hacker, hackers, linux, software, download, downloads, free, community, mp3, forum, forums, bulletin, board, boards, bbs, php, survey, poll, polls, kernel, comment, comments, portal, odp, open, source, opensource, FreeSoftware, gnu, gpl, license, Unix, *nix, mysql, sql, database, databases, web site, weblog, guru, module, modules, theme, themes, cms, content management', '_MD_AM_METAKEYDSC', 'textarea', 'text', 0)");
	$dbm->insert('config', " VALUES (39, 0, 3, 'footer', '_MD_AM_FOOTER', 'Powered by XOOPS 2.0 &copy; 2001-" . date('Y', time()) . " <a href=\"http://xoops.sourceforge.net/\" target=\"_blank\">The XOOPS Project</a>', '_MD_AM_FOOTERDSC', 'textarea', 'text', 20)");
	$dbm->insert('config', " VALUES (40, 0, 4, 'censor_enable', '_MD_AM_DOCENSOR', '0', '_MD_AM_DOCENSORDSC', 'yesno', 'int', 0)");
	$dbm->insert('config', " VALUES (41, 0, 4, 'censor_words', '_MD_AM_CENSORWRD', '".addslashes(serialize(array('fuck', 'shit')))."', '_MD_AM_CENSORWRDDSC', 'textarea', 'array', 1)");
	$dbm->insert('config', " VALUES (42, 0, 4, 'censor_replace', '_MD_AM_CENSORRPLC', '#OOPS#', '_MD_AM_CENSORRPLCDSC', 'textbox', 'text', 2)");
	$dbm->insert('config', " VALUES (43, 0, 3, 'meta_robots', '_MD_AM_METAROBOTS', 'index,follow', '_MD_AM_METAROBOTSDSC', 'select', 'text', 2)");
	$dbm->insert('config', " VALUES (44, 0, 5, 'enable_search', '_MD_AM_DOSEARCH', '1', '_MD_AM_DOSEARCHDSC', 'yesno', 'int', 0)");
	$dbm->insert('config', " VALUES (45, 0, 5, 'keyword_min', '_MD_AM_MINSEARCH', '5', '_MD_AM_MINSEARCHDSC', 'textbox', 'int', 1)");
	$dbm->insert('config', " VALUES (46, 0, 2, 'avatar_minposts', '_MD_AM_AVATARMP', '0', '_MD_AM_AVATARMPDSC', 'textbox', 'int', 15)");
	$dbm->insert('config', " VALUES (47, 0, 1, 'enable_badips', '_MD_AM_DOBADIPS', '0', '_MD_AM_DOBADIPSDSC', 'yesno', 'int', 40)");
	$dbm->insert('config', " VALUES (48, 0, 3, 'meta_rating', '_MD_AM_METARATING', 'general', '_MD_AM_METARATINGDSC', 'select', 'text', 4)");
	$dbm->insert('config', " VALUES (49, 0, 3, 'meta_author', '_MD_AM_METAAUTHOR', 'XOOPS', '_MD_AM_METAAUTHORDSC', 'textbox', 'text', 6)");
	$dbm->insert('config', " VALUES (50, 0, 3, 'meta_copyright', '_MD_AM_METACOPYR', 'Copyright &copy; 2001-2003', '_MD_AM_METACOPYRDSC', 'textbox', 'text', 8)");
	$dbm->insert('config', " VALUES (51, 0, 3, 'meta_description', '_MD_AM_METADESC', 'XOOPS is a dynamic Object Oriented based open source portal script written in PHP.', '_MD_AM_METADESCDSC', 'textarea', 'text', 1)");
	$dbm->insert('config', " VALUES (52, 0, 2, 'allow_chgmail', '_MD_AM_ALLWCHGMAIL', '0', '_MD_AM_ALLWCHGMAILDSC', 'yesno', 'int', 3)");
    $dbm->insert('config', " VALUES (53, 0, 1, 'use_mysession', '_MD_AM_USEMYSESS', '0', '_MD_AM_USEMYSESSDSC', 'yesno', 'int', 19)");
	$dbm->insert('config', " VALUES (54, 0, 2, 'reg_dispdsclmr', '_MD_AM_DSPDSCLMR', 1, '_MD_AM_DSPDSCLMRDSC', 'yesno', 'int', 30)");
	$dbm->insert('config', " VALUES (55, 0, 2, 'reg_disclaimer', '_MD_AM_REGDSCLMR', '".addslashes(_INSTALL_DISCLMR)."', '_MD_AM_REGDSCLMRDSC', 'textarea', 'text', 32)");
	$dbm->insert('config', " VALUES (56, 0, 2, 'allow_register', '_MD_AM_ALLOWREG', 1, '_MD_AM_ALLOWREGDSC', 'yesno', 'int', 0)");
	$dbm->insert('config', " VALUES (57, 0, 1, 'theme_fromfile', '_MD_AM_THEMEFILE', '0', '_MD_AM_THEMEFILEDSC', 'yesno', 'int', 13)");
	$dbm->insert('config', " VALUES (58, 0, 1, 'closesite', '_MD_AM_CLOSESITE', '0', '_MD_AM_CLOSESITEDSC', 'yesno', 'int', 26)");
	$dbm->insert('config', " VALUES (59, 0, 1, 'closesite_okgrp', '_MD_AM_CLOSESITEOK', '".addslashes(serialize(array('1')))."', '_MD_AM_CLOSESITEOKDSC', 'group_multi', 'array', 27)");
	$dbm->insert('config', " VALUES (60, 0, 1, 'closesite_text', '_MD_AM_CLOSESITETXT', '"._INSTALL_L165."', '_MD_AM_CLOSESITETXTDSC', 'textarea', 'text', 28)");
	$dbm->insert('config', " VALUES (61, 0, 1, 'sslpost_name', '_MD_AM_SSLPOST', 'xoops_ssl', '_MD_AM_SSLPOSTDSC', 'textbox', 'text', 31)");
	$dbm->insert('config', " VALUES (62, 0, 1, 'module_cache', '_MD_AM_MODCACHE', '', '_MD_AM_MODCACHEDSC', 'module_cache', 'array', 50)");
	$dbm->insert('config', " VALUES (63, 0, 1, 'template_set', '_MD_AM_DTPLSET', 'default', '_MD_AM_DTPLSETDSC', 'tplset', 'other', 14)");
	$dbm->insert('config', " VALUES (64,0,6,'mailmethod','_MD_AM_MAILERMETHOD','mail','_MD_AM_MAILERMETHODDESC','select','text',4)");
	$dbm->insert('config', " VALUES (65,0,6,'smtphost','_MD_AM_SMTPHOST','a:1:{i:0;s:0:\"\";}', '_MD_AM_SMTPHOSTDESC','textarea','array',6)");
	$dbm->insert('config', " VALUES (66,0,6,'smtpuser','_MD_AM_SMTPUSER','','_MD_AM_SMTPUSERDESC','textbox','text',7)");
	$dbm->insert('config', " VALUES (67,0,6,'smtppass','_MD_AM_SMTPPASS','','_MD_AM_SMTPPASSDESC','password','text',8)");
	$dbm->insert('config', " VALUES (68,0,6,'sendmailpath','_MD_AM_SENDMAILPATH','/usr/sbin/sendmail','_MD_AM_SENDMAILPATHDESC','textbox','text',5)");
	$dbm->insert('config', " VALUES (69,0,6,'from','_MD_AM_MAILFROM','','_MD_AM_MAILFROMDESC','textbox','text',1)");
	$dbm->insert('config', " VALUES (70,0,6,'fromname','_MD_AM_MAILFROMNAME','','_MD_AM_MAILFROMNAMEDESC','textbox','text',2)");
	$dbm->insert('config', " VALUES (71, 0, 1, 'sslloginlink', '_MD_AM_SSLLINK', 'https://', '_MD_AM_SSLLINKDSC', 'textbox', 'text', 33)");
	$dbm->insert('config', " VALUES (72, 0, 1, 'theme_set_allowed', '_MD_AM_THEMEOK', '".serialize(array('default'))."', '_MD_AM_THEMEOKDSC', 'theme_multi', 'array', 13)");
	$dbm->insert('config', " VALUES (73,0,6,'fromuid','_MD_AM_MAILFROMUID','1','_MD_AM_MAILFROMUIDDESC','user','int',3)");

	$dbm->insert('config', " VALUES (74,0,7,'auth_method','_MD_AM_AUTHMETHOD','xoops','_MD_AM_AUTHMETHODDESC','select','text',1)");
	$dbm->insert('config', " VALUES (75,0,7,'ldap_port','_MD_AM_LDAP_PORT','389','_MD_AM_LDAP_PORT','textbox','int',2)");
	$dbm->insert('config', " VALUES (76,0,7,'ldap_server','_MD_AM_LDAP_SERVER','your directory server','_MD_AM_LDAP_SERVER_DESC','textbox','text',3)");
	$dbm->insert('config', " VALUES (77,0,7,'ldap_base_dn','_MD_AM_LDAP_BASE_DN','dc=xoops,dc=org','_MD_AM_LDAP_BASE_DN_DESC','textbox','text',4)");
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
	$dbm->insert('config', " VALUES (88,0,7,'ldap_use_TLS','_MD_AM_LDAP_USETLS','0','_MD_AM_LDAP_USETLS_DESC','yesno','int',15)");					


	// default the default theme

    $time = time();
    $dbm->insert('tplset', " VALUES (1, 'default', 'XOOPS Default Theme', '', ".$time.")");

//	include_once './class/cachemanager.php';
//    $cm = new cache_manager;
//	$skinfiles = array('1' => 'skin.html', '2' => 'style.css'
//                        , '3' => 'styleNN.css','4' =>  'styleMAC.css'
//                        , '5' => 'skin_blockleft.html', '6' => 'skin_blockright.html'
//                        , '7' => 'skin_blockcenter_l.html', '8' => 'skin_blockcenter_c.html'
//                        , '9' => 'skin_blockcenter_r.html');
//    foreach ($skinfiles as $key => $skinfile) {
//        if(preg_match('/\.css$/', $skinfile)) {
//            $type = 'css';
//        }else{
//            $type = 'skin';
//        }
//        $dbm->insert('tplfile', " VALUES ($key, 0, '', 'default', '$skinfile', '', $time, $time, '$type')");

//        $fp = fopen('./templates/default_skin/'.$skinfile, 'r');
//        $skinsource = fread($fp, filesize('./templates/default_skin/'.$skinfile));
//        fclose($fp);
//        $dbm->insert('tplsource', " (tpl_id, tpl_source) VALUES ($key, '".addslashes($skinsource)."')");
//        if(preg_match('/\.css$/',$skinfile)) {
//            $cm->write($skinfile, $skinsource);
//        }
//    }

        $dbm->query("INSERT INTO ".$dbm->prefix('group_permission')." (gperm_groupid, gperm_itemid) SELECT groupid, block_id FROM ".$dbm->prefix('groups_blocks_link'));
		$dbm->query("UPDATE ".$dbm->prefix('group_permission')." SET gperm_name = 'block_read'");
    	$dbm->query("INSERT INTO ".$dbm->prefix('group_permission')." (gperm_groupid, gperm_itemid) SELECT groupid, mid FROM ".$dbm->prefix('groups_modules_link') ." WHERE type='A'");
		$dbm->query("UPDATE ".$dbm->prefix('group_permission')." SET gperm_name = 'module_admin' WHERE gperm_name = ''");
    	$dbm->query("INSERT INTO ".$dbm->prefix('group_permission')." (gperm_groupid, gperm_itemid) SELECT groupid, mid FROM ".$dbm->prefix('groups_modules_link')." WHERE type='R'");
		$dbm->query("UPDATE ".$dbm->prefix('group_permission')." SET gperm_name = 'module_read' WHERE gperm_name = ''");
		$dbm->query("UPDATE ".$dbm->prefix('group_permission')." SET gperm_modid = 1");
		$dbm->query('DROP TABLE '.$dbm->prefix('groups_blocks_link'));
		$dbm->query('DROP TABLE '.$dbm->prefix('groups_modules_link'));

	// insert some more data
	$result = $dbm->queryFromFile('./sql/'.XOOPS_DB_TYPE.'.data.sql');

	$content = $dbm->report();
    //$content .= $cm->report();
	$b_next = array('updateModules', _INSTALL_L14);
	include 'install_tpl.php';
	break;

case 'updateModules':
	$b_next = array('updateModules_go', _INSTALL_L137);
	$content = "<p>"._INSTALL_L141."</p>\n";
	include 'install_tpl.php';
	break;

case 'updateModules_go':
	unset($xoopsOption['nocommon']);
	include_once "../mainfile.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
  <title>XOOPS Custom Installation</title>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo _INSTALL_CHARSET ?>" />
  <style type="text/css" media="all"><!-- @import url(../xoops.css); --></style>
  <link rel="stylesheet" type="text/css" media="all" href="style.css" />
</head>
<body style="margin: 0; padding: 0;">
<form action='index.php' method='post'>
<table width="778" align="center" cellpadding="0" cellspacing="0" background="img/bg_table.gif">
  <tr>
    <td width="150"><img src="img/hbar_left.gif" width="100%" height="23" alt="" /></td>
    <td width="478" background="img/hbar_middle.gif">&nbsp;</td>
    <td width="150"><img src="img/hbar_right.gif" width="100%" height="23" alt="" /></td>
  </tr>
  <tr>
    <td width="150"><a href="index.php"><img src="img/logo.gif" width="150" height="80" alt="" /></a></td>
    <td width="478" background="img/bg_darkblue.gif">&nbsp;</td>
    <td width="150"><img src="img/xoops2.gif" width="100%" height="80"></td>
  </tr>
  <tr>
    <td width="150"><img src="img/hbar_left.gif" width="100%" height="23" alt="" /></td>
    <td width="478" background="img/hbar_middle.gif">&nbsp;</td>
    <td width="150"><img src="img/hbar_right.gif" width="100%" height="23" alt="" /></td>
  </tr>
</table>

<table width="778" align="center" cellspacing="0" cellpadding="0" background="img/bg_table.gif"
  <tr>
    <td width='5%'>&nbsp;</td>
    <td colspan="3"><h4 style="margin-top: 10px; margin-bottom: 5px; padding: 10px;"><?php echo _INSTALL_L142;?></h4><div style="padding: 10px; text-align:left;">
<?php
	$module_handler =& xoops_gethandler('module');
	$modules =& $module_handler->getObjects(null, true);
	foreach (array_keys($modules) as $mid) {
		echo '<h5>'.$modules[$mid]->getVar('name').'</h5>';
		$dirname = $modules[$mid]->getVar('dirname');
		if (is_dir(XOOPS_ROOT_PATH.'/modules/'.$dirname)) {
			$modules[$mid]->loadInfoAsVar($dirname, false);
			if (!$module_handler->insert($modules[$mid])) {
				echo '<p>Could not update '.$modules[$mid]->getVar('name').'</p>';
			} else {
				$newmid = $modules[$mid]->getVar('mid');
				$msgs = array();
				$msgs[] = 'Module data updated.';
				$tplfile_handler =& xoops_gethandler('tplfile');
				$templates = $modules[$mid]->getInfo('templates');
				if ($templates != false) {
					$msgs[] = 'Generating templates...';
					foreach ($templates as $tpl) {
						$tpl['file'] = trim($tpl['file']);
						$tpldata =& xoops_module_gettemplate($dirname, $tpl['file']);
						$tplfile =& $tplfile_handler->create();
						$tplfile->setVar('tpl_refid', $newmid);
						$tplfile->setVar('tpl_lastimported', 0);
						$tplfile->setVar('tpl_lastmodified', time());
						if (preg_match("/\.css$/i", $tpl['file'])) {
							$tplfile->setVar('tpl_type', 'css');
						} else {
							$tplfile->setVar('tpl_type', 'module');
							//if ($xoopsConfig['default_theme'] == 'default') {
							//	include_once XOOPS_ROOT_PATH.'/class/template.php';
							//	xoops_template_touch($tplfile->getVar('tpl_id'));
							//}
						}
						$tplfile->setVar('tpl_source', $tpldata, true);
						$tplfile->setVar('tpl_module', $dirname);
						$tplfile->setVar('tpl_tplset', 'default');
						$tplfile->setVar('tpl_file', $tpl['file'], true);
						$tplfile->setVar('tpl_desc', $tpl['description'], true);
						if (!$tplfile_handler->insert($tplfile)) {
							$msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not insert template <b>'.$tpl['file'].'</b> to the database.</span>';
						} else {
							$msgs[] = '&nbsp;&nbsp;Template <b>'.$tpl['file'].'</b> inserted to the database.';
						}
						unset($tpldata);
					}
				}
				$blocks = $modules[$mid]->getInfo('blocks');
				$msgs[] = 'Rebuilding blocks...';
				$showfuncs = array();
				$funcfiles = array();
				if ($blocks != false) {
					$count = count($blocks);
					include_once(XOOPS_ROOT_PATH.'/class/xoopsblock.php');
					for ( $i = 1; $i <= $count; $i++ ) {
						if (isset($blocks[$i]['show_func']) && $blocks[$i]['show_func'] != '' && isset($blocks[$i]['file']) && $blocks[$i]['file'] != '') {
							$editfunc = isset($blocks[$i]['edit_func']) ? $blocks[$i]['edit_func'] : '';
							$showfuncs[] = $blocks[$i]['show_func'];
							$funcfiles[] = $blocks[$i]['file'];
							$template = '';
							if ((isset($blocks[$i]['template']) && trim($blocks[$i]['template']) != '')) {
								$content =& xoops_module_gettemplate($dirname, $blocks[$i]['template'], true);
								$template = $blocks[$i]['template'];
							}
							if (!$content) {
								$content = '';
							}
							$options = '';
							if (isset($blocks[$i]['options']) && $blocks[$i]['options'] != '') {
								$options = $blocks[$i]['options'];
							}
							$sql = "SELECT bid, name FROM ".$xoopsDB->prefix('newblocks')." WHERE mid=".$mid." AND func_num=".$i;
							$fresult = $xoopsDB->query($sql);
							$fcount = 0;
							while ($fblock = $xoopsDB->fetchArray($fresult)) {
								$fcount++;
								$sql = "UPDATE ".$xoopsDB->prefix("newblocks")." SET name='".addslashes($blocks[$i]['name'])."', title='".addslashes($blocks[$i]['name'])."', dirname='".addslashes($dirname)."',  func_file='".addslashes($blocks[$i]['file'])."', show_func='".addslashes($blocks[$i]['show_func'])."', template='".addslashes($template)."', edit_func='".addslashes($editfunc)."', options='".addslashes($options)."', content='', template='".$template."', last_modified=".time()." WHERE bid=".$fblock['bid'];
								$result = $xoopsDB->query($sql);
								if (!$result) {
									$msgs[] = '&nbsp;&nbsp;ERROR: Could not update '.$fblock['name'];
								} else {
									$msgs[] = '&nbsp;&nbsp;Block <b>'.$fblock['name'].'</b> updated. Block ID: <b>'.$fblock['bid'].'</b>';
									if ($template != '') {
										$tplfile =& $tplfile_handler->create();
										$tplfile->setVar('tpl_refid', $fblock['bid']);
										$tplfile->setVar('tpl_source', $content, true);
										$tplfile->setVar('tpl_tplset', 'default');
										$tplfile->setVar('tpl_file', $blocks[$i]['template']);
										$tplfile->setVar('tpl_module', $dirname);
										$tplfile->setVar('tpl_type', 'block');
										$tplfile->setVar('tpl_desc', $blocks[$i]['description'], true);
										$tplfile->setVar('tpl_lastimported', 0);
										$tplfile->setVar('tpl_lastmodified', time());
										if (!$tplfile_handler->insert($tplfile)) {
											$msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not insert template <b>'.$blocks[$i]['template'].'</b> to the database.</span>';
										} else {
											$msgs[] = '&nbsp;&nbsp;Template <b>'.$blocks[$i]['template'].'</b> inserted to the database.';
											//if ($xoopsConfig['default_theme'] == 'default') {
											//	if (!xoops_template_touch($tplfile[0]->getVar('tpl_id'))) {
											//		$msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not recompile template <b>'.$blocks[$i]['template'].'</b>.</span>';
											//	} else {
											//		$msgs[] = '&nbsp;&nbsp;Template <b>'.$blocks[$i]['template'].'</b> recompiled.';
											//	}
											//}
										}
									}
								}
							}
							if ($fcount == 0) {
								$newbid = $xoopsDB->genId($xoopsDB->prefix('newblocks').'_bid_seq');
								$block_name = addslashes($blocks[$i]['name']);
								$sql = "INSERT INTO ".$xoopsDB->prefix("newblocks")." (bid, mid, func_num, options, name, title, content, side, weight, visible, block_type, isactive, dirname, func_file, show_func, edit_func, template, last_modified) VALUES (".$newbid.", ".$mid.", ".$i.",'".addslashes($options)."','".$block_name."', '".$block_name."', '', 0, 0, 0, 'M', 1, '".addslashes($dirname)."', '".addslashes($blocks[$i]['file'])."', '".addslashes($blocks[$i]['show_func'])."', '".addslashes($editfunc)."', '".$template."', ".time().")";
								$result = $xoopsDB->query($sql);
								if (!$result) {
									$msgs[] = '&nbsp;&nbsp;ERROR: Could not create '.$blocks[$i]['name'];
								} else {
									if (empty($newbid)) {
										$newbid = $xoopsDB->getInsertId();
									}
									if ($template != '') {
										$tplfile =& $tplfile_handler->create();
										$tplfile->setVar('tpl_module', $dirname);
										$tplfile->setVar('tpl_refid', $newbid);
										$tplfile->setVar('tpl_source', $content, true);
										$tplfile->setVar('tpl_tplset', 'default');
										$tplfile->setVar('tpl_file', $blocks[$i]['template'], true);
										$tplfile->setVar('tpl_type', 'block');
										$tplfile->setVar('tpl_lastimported', 0);
										$tplfile->setVar('tpl_lastmodified', time());
										$tplfile->setVar('tpl_desc', $blocks[$i]['description'], true);
										if (!$tplfile_handler->insert($tplfile)) {
											$msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not insert template <b>'.$blocks[$i]['template'].'</b> to the database.</span>';
										} else {
											$msgs[] = '&nbsp;&nbsp;Template <b>'.$blocks[$i]['template'].'</b> inserted to the database.';
										}
									}
									$msgs[] = '&nbsp;&nbsp;Block <b>'.$blocks[$i]['name'].'</b> created. Block ID: <b>'.$newbid.'</b>';
								}
							}
						}
					}
				}
				$block_arr = XoopsBlock::getByModule($mid);
				foreach ($block_arr as $block) {
					if (!in_array($block->getVar('show_func'), $showfuncs) || !in_array($block->getVar('func_file'), $funcfiles)) {
						$sql = sprintf("DELETE FROM %s WHERE bid = %u", $xoopsDB->prefix('newblocks'), $block->getVar('bid'));
						if(!$xoopsDB->query($sql)) {
							$msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not delete block <b>'.$block->getVar('name').'</b>. Block ID: <b>'.$block->getVar('bid').'</b></span>';
						} else {
							$msgs[] = '&nbsp;&nbsp;Block <b>'.$block->getVar('name').' deleted. Block ID: <b>'.$block->getVar('bid').'</b>';
						}
					}
				}

				$configs = $modules[$mid]->getInfo('config');
				if ($configs != false) {
					if ($modules[$mid]->getVar('hascomments') != 0) {
						include_once(XOOPS_ROOT_PATH.'/include/comment_constants.php');
						array_push($configs, array('name' => 'com_rule', 'title' => '_CM_COMRULES', 'description' => '', 'formtype' => 'select', 'valuetype' => 'int', 'default' => 1, 'options' => array('_CM_COMAPPROVEALL' => XOOPS_COMMENT_APPROVEALL, '_CM_COMAPPROVEUSER' => XOOPS_COMMENT_APPROVEUSER, '_CM_COMAPPROVEADMIN' => XOOPS_COMMENT_APPROVEADMIN)));
						array_push($configs, array('name' => 'com_anonpost', 'title' => '_CM_COMANONPOST', 'description' => '', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => 0));
					}
				} else {
					if ($modules[$mid]->getVar('hascomments') != 0) {
						$configs = array();
						include_once(XOOPS_ROOT_PATH.'/include/comment_constants.php');
						$configs[] = array('name' => 'com_rule', 'title' => '_CM_COMRULES', 'description' => '', 'formtype' => 'select', 'valuetype' => 'int', 'default' => 1, 'options' => array('_CM_COMAPPROVEALL' => XOOPS_COMMENT_APPROVEALL, '_CM_COMAPPROVEUSER' => XOOPS_COMMENT_APPROVEUSER, '_CM_COMAPPROVEADMIN' => XOOPS_COMMENT_APPROVEADMIN));
						array_push($configs, array('name' => 'com_anonpost', 'title' => '_CM_COMANONPOST', 'description' => '', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => 0));
					}
				}
				// RMV-NOTIFY
				if ($modules[$mid]->getVar('hasnotification') != 0) {
					if (empty($configs)) {
						$configs = array();
					}
					include_once(XOOPS_ROOT_PATH.'/include/notification_constants.php');
					$configs[] = array ('name' => 'notification_enabled', 'title' => '_NOT_CONFIG_ENABLED', 'description' => '_NOT_CONFIG_ENABLEDDSC', 'formtype' => 'select', 'valuetype' => 'int', 'default' => XOOPS_NOTIFICATION_ENABLEBOTH, 'options' => $options);
				}

				if ($configs != false) {
					$msgs[] = 'Adding module config data...';
					$config_handler =& xoops_gethandler('config');
					$order = 0;
					foreach ($configs as $config) {
						$confobj =& $config_handler->createConfig();
						$confobj->setVar('conf_modid', $newmid);
						$confobj->setVar('conf_catid', 0);
						$confobj->setVar('conf_name', $config['name']);
						$confobj->setVar('conf_title', $config['title'], true);
						$confobj->setVar('conf_desc', $config['description'], true);
						$confobj->setVar('conf_formtype', $config['formtype']);
						$confobj->setVar('conf_valuetype', $config['valuetype']);
						$confobj->setVar('conf_value', $config['default'], true);
						$confobj->setVar('conf_order', $order);
						$confop_msgs = '';
						if (isset($config['options']) && is_array($config['options'])) {
							foreach ($config['options'] as $key => $value) {
								$confop =& $config_handler->createConfigOption();
								$confop->setVar('confop_name', $key, true);
								$confop->setVar('confop_value', $value, true);
								$confobj->setConfOptions($confop);
								$confop_msgs .= '<br />&nbsp;&nbsp;&nbsp;&nbsp;Config option added. Name: <b>'.$key.'</b> Value: <b>'.$value.'</b>';
								unset($confop);
							}
						}
						$order++;
						if ($config_handler->insertConfig($confobj) != false) {
							$msgs[] = '&nbsp;&nbsp;Config <b>'.$config['name'].'</b> added to the database.'.$confop_msgs;
						} else {
							$msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not insert config <b>'.$config['name'].'</b> to the database.</span>';
						}
						unset($confobj);
					}
					unset($configs);
				}
				foreach ($msgs as $msg) {
					echo '<code>'.$msg.'</code><br />';
				}
			}
			// data for table 'block_module_link'
			include_once './class/dbmanager.php';
    		$dbm = new db_manager;
    		$sql = 'SELECT bid, side FROM '.$dbm->prefix('newblocks');
    		$result = $dbm->query($sql);

    		while ($myrow = $dbm->fetchArray($result)) {
        		if ($myrow['side'] == 0) {
            		$dbm->insert("block_module_link", " VALUES (".$myrow['bid'].", 0)");
        		} else {
            		$dbm->insert("block_module_link", " VALUES (".$myrow['bid'].", -1)");
        		}
    		}
		}
		echo '<br />';
		flush();
		sleep(1);
	}
?>
	</div></td>
    <td width='5%'>&nbsp;</td>
  </tr>
  <tr>
    <td width='5%'>&nbsp;</td>
    <td width='35%' align='left'>&nbsp;</td>
    <td width='20%' align='center'>&nbsp;</td>
    <td width='35%' align='right'><span style='font-size:85%;'><?php echo _INSTALL_L14;?> >></span> <input type='hidden' name='op' value='updateComments' /><input type='submit' name='submit' value='<?php echo _INSTALL_L47;?>' /></td>
    <td width='5%'>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5">&nbsp;</td>
  </tr>
</table>

<table width="778" cellspacing="0" cellpadding="0" align="center" background="img/bg_table.gif">
  <tr>
    <td width="150"><img src="img/hbar_left.gif" width="100%" height="23" alt="" /></td>
    <td width="478" background="img/hbar_middle.gif">&nbsp;</td>
    <td width="150"><img src="img/hbar_installer_right.gif" width="100%" height="23" alt="" /></td>
  </tr>
</table>
</form>
</body>
</html>
<?php
	break;

case 'updateComments':
	$content = "<p>"._INSTALL_L149."</p>\n";
	$b_next = array('updateComments_go', _INSTALL_L138);
	include 'install_tpl.php';
	break;

case 'updateComments_go':
	unset($xoopsOption['nocommon']);
	include '../mainfile.php';
	include '../class/xoopscomments.php';
	include '../include/comment_constants.php';
	$module_handler =& xoops_gethandler('module');
	$old_commentd_mods = array('news' => 'comments', 'xoopspoll' => 'xoopspollcomments');
	$title = _INSTALL_L147;
	$content = '';
	foreach ($old_commentd_mods as $module => $com_table) {
		$moduleobj =& $module_handler->getByDirname($module);
		if (is_object($moduleobj)) {
			$content .= '<h5>'.$moduleobj->getVar('name').'</h5>';
			$comment_handler =& xoops_gethandler('comment');
			$criteria = new CriteriaCompo();
			$criteria->setOrder('DESC');
			$criteria->setSort('com_id');
			$criteria->setLimit(1);
			$last_comment =& $comment_handler->getObjects($criteria);
			$offset = (is_array($last_comment) && count($last_comment) > 0) ? $last_comment[0]->getVar('com_id') : 0;
			$xc = new XoopsComments($xoopsDB->prefix($com_table));
			$top_comments =& $xc->getAllComments(array('pid=0'));

			foreach ($top_comments as $tc) {
				$sql = sprintf("INSERT INTO %s (com_id, com_pid, com_modid, com_icon, com_title, com_text, com_created, com_modified, com_uid, com_ip, com_sig, com_itemid, com_rootid, com_status, dohtml, dosmiley, doxcode, doimage, dobr) VALUES (%u, %u, %u, '%s', '%s', '%s', %u, %u, %u, '%s', %u, %u, %u, %u, %u, %u, %u, %u, %u)", $xoopsDB->prefix('xoopscomments'), $tc->getVar('comment_id') + $offset, 0, $moduleobj->getVar('mid'), '', addslashes($tc->getVar('subject', 'n')), addslashes($tc->getVar('comment', 'n')), $tc->getVar('date'), $tc->getVar('date'), $tc->getVar('user_id'), $tc->getVar('ip'), 0, $tc->getVar('item_id'), $tc->getVar('comment_id') + $offset, XOOPS_COMMENT_ACTIVE, 0, 1, 1, 1, 1);

				if (!$xoopsDB->query($sql)) {
					$content .= _NGIMG.sprintf(_INSTALL_L146, $tc->getVar('comment_id') + $offset).'<br />';
				} else {
					$content .= _OKIMG.sprintf(_INSTALL_L145, $tc->getVar('comment_id') + $offset).'<br />';
					$child_comments = $tc->getCommentTree();
					foreach ($child_comments as $cc) {
						$sql = sprintf("INSERT INTO %s (com_id, com_pid, com_modid, com_icon, com_title, com_text, com_created, com_modified, com_uid, com_ip, com_sig, com_itemid, com_rootid, com_status, dohtml, dosmiley, doxcode, doimage, dobr) VALUES (%u, %u, %u, '%s', '%s', '%s', %u, %u, %u, '%s', %u, %u, %u, %u, %u, %u, %u, %u, %u)", $xoopsDB->prefix('xoopscomments'), $cc->getVar('comment_id') + $offset, $cc->getVar('pid') + $offset, $moduleobj->getVar('mid'), '', addslashes($cc->getVar('subject', 'n')), addslashes($cc->getVar('comment', 'n')), $cc->getVar('date'), $cc->getVar('date'), $cc->getVar('user_id'), $cc->getVar('ip'), 0, $cc->getVar('item_id'), $tc->getVar('comment_id') + $offset, XOOPS_COMMENT_ACTIVE, 0, 1, 1, 1, 1);
						if (!$xoopsDB->query($sql)) {
							$content .= _NGIMG.sprintf(_INSTALL_L146, $cc->getVar('comment_id') + $offset).'<br />';
						} else {
							$content .= _OKIMG.sprintf(_INSTALL_L145, $cc->getVar('comment_id') + $offset).'<br />';
						}
					}
				}
			}
		}
	}
	$xoopsDB->query('ALTER TABLE '.$xoopsDB->prefix('xoopscomments').' CHANGE com_id com_id mediumint(8) unsigned NOT NULL auto_increment PRIMARY KEY');
	$b_next = array('updateSmilies', _INSTALL_L14);
	include 'install_tpl.php';
	break;

case 'updateSmilies':
	$content = '<p>'._INSTALL_L150.'</p>';
	$b_next = array('updateSmilies_go', _INSTALL_L140);
	include 'install_tpl.php';
	break;


case 'updateSmilies_go':
	unset($xoopsOption['nocommon']);
	include('../mainfile.php');
	$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix('smiles'));
	$content = '';
	$title = _INSTALL_L155;
	if (!defined('XOOPS_UPLOAD_PATH')) {
		define('XOOPS_UPLOAD_PATH', '../uploads');
	}
	while ($smiley = $xoopsDB->fetchArray($result)) {
		if (file_exists('../images/smilies/'.$smiley['smile_url']) && false != $fp = fopen('../images/smilies/'.$smiley['smile_url'], 'rb')) {
			$binary = fread($fp, filesize('../images/smilies/'.$smiley['smile_url']));
			fclose($fp);
			if (!preg_match("/\.([a-zA-Z0-9]+)$/", $smiley['smile_url'], $matched)) {
            	continue;
        	}
            $newsmiley = uniqid('smil').'.'.strtolower($matched[1]);
			if (false != $fp = fopen(XOOPS_UPLOAD_PATH.'/'.$newsmiley, 'wb')) {
				if (-1 != fwrite($fp, $binary)) {
					$xoopsDB->query("UPDATE ".$xoopsDB->prefix('smiles')." SET smile_url='".$newsmiley."' WHERE id=".$smiley['id']);
					$content .= _OKIMG.sprintf(_INSTALL_L154, $smiley['smile_url']).'<br />';
				} else {
					$content .= _NGIMG.sprintf(_INSTALL_L153, $smiley['smile_url']).'<br />';
				}
				fclose($fp);
			}
		} else {
			$content .= _OKIMG.sprintf(_INSTALL_L152, $smiley['smile_url']).'<br />';
		}
	}
	$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix('ranks'));
	while ($rank = $xoopsDB->fetchArray($result)) {
		if (file_exists('../images/ranks/'.$rank['rank_image']) && false != $fp = fopen('../images/ranks/'.$rank['rank_image'], 'rb')) {
			$binary = fread($fp, filesize('../images/ranks/'.$rank['rank_image']));
			fclose($fp);
			if (!preg_match("/\.([a-zA-Z0-9]+)$/", $rank['rank_image'], $matched)) {
            	continue;
        	}
            $newrank = uniqid('rank').'.'.strtolower($matched[1]);
			if (false != $fp = fopen(XOOPS_UPLOAD_PATH.'/'.$newrank, 'wb')) {
				if (-1 != fwrite($fp, $binary)) {
					$content .= _OKIMG.sprintf(_INSTALL_L154, $rank['rank_image']).'<br />';
					$xoopsDB->query("UPDATE ".$xoopsDB->prefix('ranks')." SET rank_image='".$newrank."' WHERE rank_id=".$rank['rank_id']);
				} else {
					$content .= _NGIMG.sprintf(_INSTALL_L153, $rank['rank_image']).'<br />';
				}
				fclose($fp);
			}
		} else {
			$content .= _OKIMG.sprintf(_INSTALL_L152, $rank['rank_image']).'<br />';
		}
	}
	$b_next = array('updateAvatars', _INSTALL_L14);
	include 'install_tpl.php';
	break;

case 'updateAvatars':
	$content = '<p>'._INSTALL_L151.'</p>';
	$b_next = array('updateAvatars_go', _INSTALL_L139);
	include 'install_tpl.php';
	break;

case 'updateAvatars_go':
	unset($xoopsOption['nocommon']);
	include('../mainfile.php');
	$content = '';
	$title = _INSTALL_L156;
	$avatars = getImageFileList(XOOPS_ROOT_PATH.'/images/avatar/users/');
	$xoopsDB->query("UPDATE ".$xoopsDB->prefix('users')." SET user_avatar='blank.gif'");
	$avt_handler =& xoops_gethandler('avatar');
	if (!defined('XOOPS_UPLOAD_PATH')) {
		define('XOOPS_UPLOAD_PATH', '../uploads');
	}
	foreach ($avatars as $avatar_file) {
		if (preg_match("/^([0-9]+)\.([a-zA-Z]+)$/", $avatar_file, $matched)) {
			$user_id = intval($matched[1]);
			if ($user_id > 0 && false != $fp = fopen('../images/avatar/users/'.$avatar_file, 'rb')) {
				$binary = fread($fp, filesize('../images/avatar/users/'.$avatar_file));
				fclose($fp);
            	$newavatar = uniqid('cavt').'.'.strtolower($matched[2]);
				if (false != $fp = fopen(XOOPS_UPLOAD_PATH.'/'.$newavatar, 'wb')) {
					if (-1 != fwrite($fp, $binary)) {
						$error = false;
						if (!$xoopsDB->query("UPDATE ".$xoopsDB->prefix('users')." SET user_avatar='".$newavatar."' WHERE uid=".$user_id)) {
							$error = true;
						} else {
							$avatar =& $avt_handler->create();
							$avatar->setVar('avatar_file', $newavatar);
							$avatar->setVar('avatar_name', 'custom');
							$avatar->setVar('avatar_mimetype', '');
							$avatar->setVar('avatar_display', 1);
							$avatar->setVar('avatar_type', 'C');
							if(!$avt_handler->insert($avatar)) {
								$error = true;
							} else {
								$avt_handler->addUser($avatar->getVar('avatar_id'), $user['uid']);
							}
						}
						if (false != $error) {
							$content .= _NGIMG.sprintf(_INSTALL_L153, $avatar_file).'<br />';
							@unlink(XOOPS_UPLOAD_PATH.'/'.$newavatar);
						} else {
							$content .= _OKIMG.sprintf(_INSTALL_L154, $avatar_file).'<br />';
						}
					} else {
						$content .= _NGIMG.sprintf(_INSTALL_L153, $avatar_file).'<br />';
						$xoopsDB->query("UPDATE ".$xoopsDB->prefix('users')." SET user_avatar='blank.gif' WHERE uid=".$user_id);
					}
					fclose($fp);
				}
			} else {
				$content .= _NGIMG.sprintf(_INSTALL_L152, $avatar_file).'<br />';
			}
		}
	}

	$b_next = array('finish', _INSTALL_L14);
	include 'install_tpl.php';
	break;


case "siteInit":
    include_once "../mainfile.php";

    $content = "<table align='center' width='70%'>\n";
    $content .= "<tr><td colspan='2' align='center'>"._INSTALL_L36."</td></tr>\n";
    $content .= "<tr><td align='right'><b>"._INSTALL_L37."</b></td><td><input type=\"text\" name=\"adminname\" /></td></tr>\n";
    $content .= "<tr><td align='right'><b>"._INSTALL_L38."</b></td><td><input type='text' name='adminmail' value='' maxlength='60' /></td></tr>\n";
    $content .= "<tr><td align='right'><b>"._INSTALL_L39."</b></td><td><input type='password' name='adminpass' /></td></tr>\n";
    $content .= "<tr><td align='right'><b>"._INSTALL_L74."</b></td><td><input type='password' name='adminpass2' /></td></tr>\n";
    $content .= "</table>\n";
    $b_next = array('insertData', _INSTALL_L116);

    include 'install_tpl.php';
    break;

case "insertData":
    $adminname = $myts->stripSlashesGPC($_POST['adminname']);
    $adminpass = $myts->stripSlashesGPC($_POST['adminpass']);
    $adminpass2 = $myts->stripSlashesGPC($_POST['adminpass2']);
    $adminmail = $myts->stripSlashesGPC($_POST['adminmail']);

    if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i",$adminmail)) {
        $content = "<p>"._INSTALL_L73."</p>\n";
        $b_back = array('', _INSTALL_L112 );
        include 'install_tpl.php';
        exit();
    }
    if ( !isset($adminname) || !isset($adminpass) || !isset($adminmail) || $adminmail == "" || $adminname =="" || $adminpass =="" || $adminpass != $adminpass2) {
        $content = "<p>"._INSTALL_L41."</p>\n";
        $b_back = array('', _INSTALL_L112 );
        include 'install_tpl.php';
        exit();
    }

    include_once "../mainfile.php";
    //include_once './include/createtables2.php';
    include_once './makedata.php';
    include_once './class/dbmanager.php';
    $dbm = new db_manager;
    include_once './class/cachemanager.php';
    $cm = new cache_manager;

    $language = check_language($language);
    if ( file_exists("./language/".$language."/install2.php") ) {
        include_once "./language/".$language."/install2.php";
    } elseif ( file_exists("./language/english/install2.php") ) {
        include_once "./language/english/install2.php";
        $language = 'english';
    } else {
        echo 'no language file (install2.php).';
        exit();
    }

    //$tables = array();
    $result = $dbm->queryFromFile('./sql/'.XOOPS_DB_TYPE.'.data.sql');
    $result = $dbm->queryFromFile('./language/'.$language.'/'.XOOPS_DB_TYPE.'.lang.data.sql');
    $group = make_groups($dbm);
    $result = make_data($dbm, $cm, $adminname, $adminpass, $adminmail, $language, $group);
    $content = $dbm->report();
    $content .= $cm->report();
    include_once "./class/mainfilemanager.php";
    $mm = new mainfile_manager("../mainfile.php");
    foreach($group as $key => $val){
        $mm->setRewrite($key, intval($val));
    }
    $result = $mm->doRewrite();
    $content .= $mm->report();

    $b_next = array('finish', _INSTALL_L117);
    $title = _INSTALL_L116;
    setcookie('xoops_session', '', time() - 3600);
    include 'install_tpl.php';

    break;

case 'finish':

    $title = _INSTALL_L32;
    $content = "<table width='60%' align='center'><tr><td align='left'>\n";
    include './language/'.$language.'/finish.php';
    $content .= "</td></tr></table>\n";
    include 'install_tpl.php';
    break;
}

/*
 * gets list of name of directories inside a directory
 */
function getDirList($dirname)
{
	require_once dirname(dirname(__FILE__))."/class/xoopslists.php";
	return XoopsLists::getDirListAsArray($dirname);
}

/*
 * gets list of name of files within a directory
 */
function getImageFileList($dirname)
{
	require_once dirname(dirname(__FILE__))."/class/xoopslists.php";
	return XoopsLists::getImgListAsArray($dirname);
}

function &xoops_module_gettemplate($dirname, $template, $block=false)
{
	if ($block) {
		$path = XOOPS_ROOT_PATH.'/modules/'.$dirname.'/templates/blocks/'.$template;
	} else {
		$path = XOOPS_ROOT_PATH.'/modules/'.$dirname.'/templates/'.$template;
	}
	if (!file_exists($path)) {
		return false;
	} else {
		$lines = file($path);
	}
	if (!$lines) {
		return false;
	}
	$ret = '';
	$count = count($lines);
	for ($i = 0; $i < $count; $i++) {
		$ret .= str_replace("\n", "\r\n", str_replace("\r\n", "\n", $lines[$i]));
	}
	return $ret;
}

function check_language($language){
     if ( file_exists('../modules/system/language/'.$language.'/modinfo.php') ) {
        return $language;
    } else {
        return 'english';
    }
}
?>