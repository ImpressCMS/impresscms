<?php
// $Id: cp_functions.php,v 1.3 2007/08/30 19:03:16 marcan Exp $
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
define ( 'XOOPS_CPFUNC_LOADED', 1 );

/**
 * Creamos la variable para uso de Smarty
 */
include_once XOOPS_ROOT_PATH . '/class/template.php';
global $icmsAdminTpl;

$icmsAdminTpl = new XoopsTpl ( );

$icmsAdminTpl->assign ( 'xoops_url', XOOPS_URL );
$icmsAdminTpl->assign ( 'xoops_sitename', $xoopsConfig ['sitename'] );

function xoops_cp_header($ret = 0) {
	global $xoopsConfig, $xoopsModule, $xoopsUser, $icmsAdminTpl, $im_multilanguageConfig, $icmsPreloadHandler;
	
	if (! headers_sent ()) {
		header ( 'Content-Type:text/html; charset=' . _CHARSET );
		header ( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( 'Cache-Control: no-store, no-cache, must-revalidate' );
		header ( "Cache-Control: post-check=0, pre-check=0", false );
		header ( "Pragma: no-cache" );
	}
	echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>";
	echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="' . _LANGCODE . '" lang="' . _LANGCODE . '">
	<head>
	<meta http-equiv="content-type" content="text/html; charset=' . _CHARSET . '" />
	<meta http-equiv="content-language" content="' . _LANGCODE . '" />
	<title>' . htmlspecialchars ( $xoopsConfig ['sitename'], ENT_QUOTES ) . ' Administration</title>
	<script type="text/javascript" src="' . XOOPS_URL . '/include/xoops.js"></script>' . '<link rel="shortcut icon" type="image/ico" href="' . XOOPS_URL . '/favicon.ico" />
	<link rel="icon" type="image/png" href="' . XOOPS_URL . '/favicon.ico" />
	';
	echo '<link rel="stylesheet" type="text/css" media="all" href="' . XOOPS_URL . '/xoops.css" />';
	echo '<link rel="stylesheet" type="text/css" media="all" href="' . XOOPS_URL . '/modules/system/style.css" />';
	// ################# Preload Trigger adminHeader ##############
	$icmsPreloadHandler->triggerEvent ( 'adminHeader' );
	
	echo "<script type=\"text/javascript\"><!--//--><![CDATA[//><!--
startList = function() {
	if (document.all&&document.getElementById) {
		navRoot = document.getElementById(\"nav\");
		for (i=0; i<navRoot.childNodes.length; i++) {
			node = navRoot.childNodes[i];
			if (node.nodeName==\"LI\") {
				node.onmouseover=function() {
					this.className+=\" over\";
				}
				node.onmouseout=function() {
					this.className=this.className.replace(\" over\", \"\");
				}
			}
		}
	}
}
window.onload=startList;

//--><!]]></script>";
	// ################# Preload Trigger adminBeforeFooter ##############
	$icmsPreloadHandler->triggerEvent ( 'adminBeforeFooter' );
	
	echo "</head>
        <body>";
	/**
	 * Loading admin dropdown menus
	 */
	if (! file_exists ( XOOPS_CACHE_PATH . '/adminmenu_' . $xoopsConfig ['language'] . '.php' )) {
		xoops_confirm ( array ('op' => 2 ), XOOPS_URL . '/admin.php', _RECREATE_ADMINMENU_FILE );
		exit ();
	}
	$file = file_get_contents ( XOOPS_CACHE_PATH . "/adminmenu_" . $xoopsConfig ['language'] . ".php" );
	$admin_menu = eval ( 'return ' . $file . ';' );
	
	$moduleperm_handler = & xoops_gethandler ( 'groupperm' );
	$module_handler = & xoops_gethandler ( 'module' );
	foreach ( $admin_menu as $k => $navitem ) {
		if ($navitem ['id'] == 'modules') { //Getting array of allowed modules to use in admin home
			$perm_itens = array ( );
			foreach ( $navitem ['menu'] as $item ) {
				$module = $module_handler->getByDirname ( $item ['dir'] );
				$admin_perm = $moduleperm_handler->checkRight ( 'module_admin', $module->mid (), $xoopsUser->getGroups () );
				if ($admin_perm) {
					if ($item ['dir'] != 'system') {
						$perm_itens [] = $item;
					}
				}
			}
			$navitem ['menu'] = $mods = $perm_itens;
		} //end
		if ($navitem ['id'] == 'opsystem') {
			$groups = $xoopsUser->getGroups ();
			$all_ok = false;
			if (! in_array ( XOOPS_GROUP_ADMIN, $groups )) {
				$sysperm_handler = & xoops_gethandler ( 'groupperm' );
				$ok_syscats = & $sysperm_handler->getItemIds ( 'system_admin', $groups );
			} else {
				$all_ok = true;
			}
			$perm_itens = array ( );
			
			foreach ( $navitem ['menu'] as $sortarray ) {
				$column [] = $sortarray ['title'];
			}
			//sort arrays after loop
			array_multisort ( $column, SORT_ASC, $navitem ['menu'] );

			foreach ( $navitem ['menu'] as $item ) {
				if (false != $all_ok || in_array ( $item ['id'], $ok_syscats )) {
					$perm_itens [] = $item;
				}
			}
			$navitem ['menu'] = $sysprefs = $perm_itens; //Getting array of allowed system prefs
		}
		$icmsAdminTpl->append ( 'navitems', $navitem );
	}
	//icms_debug_vardump($sysprefs);
	if (count ( $sysprefs ) > 0) {
		$icmsAdminTpl->assign ( 'systemadm', 1 );
	} else {
		$icmsAdminTpl->assign ( 'systemadm', 0 );
	}
	if (count ( $mods ) > 0) {
		$icmsAdminTpl->assign ( 'modulesadm', 1 );
	} else {
		$icmsAdminTpl->assign ( 'modulesadm', 0 );
	}
	
	/**
	 * Loading options of the current module.
	 */
	if ($xoopsModule) {
		if ($xoopsModule->dirname () == 'system') {
			if (isset ( $sysprefs ) && count ( $sysprefs ) > 0) {
				for($i = count ( $sysprefs ) - 1; $i >= 0; $i = $i - 1) {
					if (isset ( $sysprefs [$i] )) {
						$reversed_sysprefs [] = $sysprefs [$i];
					}
				}
				foreach ( $reversed_sysprefs as $k ) {
					$icmsAdminTpl->append ( 'mod_options', array ('title' => $k ['title'], 'link' => $k ['link'], 'icon' => (isset ( $k ['icon'] ) && $k ['icon'] != '' ? $k ['icon'] : '') ) );
				}
			}
		} else {
			foreach ( $mods as $mod ) {
				if ($mod ['dir'] == $xoopsModule->dirname ()) {
					$m = $mod; //Getting info of the current module
					break;
				}
			}
			if (isset ( $m ['subs'] ) && count ( $m ['subs'] ) > 0) {
				for($i = count ( $m ['subs'] ) - 1; $i >= 0; $i = $i - 1) {
					if (isset ( $m ['subs'] [$i] )) {
						$reversed_module_admin_menu [] = $m ['subs'] [$i];
					}
				}
				foreach ( $reversed_module_admin_menu as $k ) {
					$icmsAdminTpl->append ( 'mod_options', array ('title' => $k ['title'], 'link' => $k ['link'], 'icon' => (isset ( $k ['icon'] ) && $k ['icon'] != '' ? $k ['icon'] : '') ) );
				}
			}
		}
		$icmsAdminTpl->assign ( 'modpath', XOOPS_URL . '/modules/' . $xoopsModule->dirname () );
		$icmsAdminTpl->assign ( 'modname', $xoopsModule->name () );
		$icmsAdminTpl->assign ( 'modid', $xoopsModule->mid () );
		$icmsAdminTpl->assign ( 'moddir', $xoopsModule->dirname () );
		$icmsAdminTpl->assign ( 'lang_prefs', _PREFERENCES );
	}
	
	/**
	 * Send to template some ml infos
	 */
	$icmsAdminTpl->assign ( 'lang_prefs', _IMPRESSCMS_PREFS );
	$icmsAdminTpl->assign ( 'ml_is_enabled', $im_multilanguageConfig ['ml_enable'] );
	
	echo $icmsAdminTpl->fetch ( XOOPS_ROOT_PATH . '/modules/system/templates/admin/system_adm_navbar.html' );
	echo "<div id='containBodyCP'><br /><div id='bodyCP'>";
	
	if ($ret)
		return $mods;
}

function xoops_cp_footer() {
	global $xoopsConfig, $xoopsLogger;
	echo "</div><br /></div>";
	echo "<div class='CPfoot'>Powered by&nbsp;" . XOOPS_VERSION . " &copy; 2007-" . date ( "Y" ) . " <a href='http://www.impresscms.org/' target='_blank'>ImpressCMS</a></div>
        </body>
      </html>
    ";
	echo $GLOBALS ['xoopsLogger']->render ( '' );
	ob_end_flush ();
}

// We need these because theme files will not be included
function OpenTable() {
	echo "<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 2px solid #2F5376;'><tr class='bg4'><td valign='top'>\n";
}

function CloseTable() {
	echo '</td></tr></table>';
}

function themecenterposts($title, $content) {
	echo '<table cellpadding="4" cellspacing="1" width="98%" class="outer"><tr><td class="head">' . $title . '</td></tr><tr><td><br />' . $content . '<br /></td></tr></table>';
}

function myTextForm($url, $value) {
	return '<form action="' . $url . '" method="post"><input type="submit" value="' . $value . '" /></form>';
}

function xoopsfwrite() {
	if ($_SERVER ['REQUEST_METHOD'] != 'POST') {
		return false;
	} else {
	
	}
	if (! xoops_refcheck ()) {
		return false;
	} else {
	
	}
	return true;
}

/**
 * Creates a multidimensional array with items of the dropdown menus of the admin panel.
 * This array will be saved, by the function xoops_module_write_admin_menu, in a cache file 
 * to preserve resources of the server and to maintain compatibility with some modules Xoops.
 * 
 * @author TheRplima
 * 
 * @return array (content of admin panel dropdown menus)
 */
function impresscms_get_adminmenu() {
	global $xoopsUser;
	
	$admin_menu = array ( );
	$modules_menu = array ( );
	$systemadm = false;
	$cont = 0;
	
	#########################################################################
	# Control Panel Home menu
	#########################################################################
	$i = 0;
	$menu [$i] ['link'] = XOOPS_URL . "/admin.php";
	$menu [$i] ['title'] = _CPHOME;
	$menu [$i] ['absolute'] = 1;
	$menu [$i] ['small'] = XOOPS_URL . "/modules/system/images/mini_cp.png";
	$i ++;
	
	$menu [$i] ['link'] = XOOPS_URL;
	$menu [$i] ['title'] = _YOURHOME;
	$menu [$i] ['absolute'] = 1;
	$menu [$i] ['small'] = XOOPS_URL . "/modules/system/images/home.png";
	$i ++;
	
	$menu [$i] ['link'] = XOOPS_URL . "/user.php?op=logout";
	$menu [$i] ['title'] = _LOGOUT;
	$menu [$i] ['absolute'] = 1;
	$menu [$i] ['small'] = XOOPS_URL . '/images/logout.png';
	
	$admin_menu [$cont] ['id'] = 'cphome';
	$admin_menu [$cont] ['text'] = _CPHOME;
	$admin_menu [$cont] ['link'] = '#';
	$admin_menu [$cont] ['menu'] = $menu;
	$cont ++;
	#########################################################################
	# end
	#########################################################################
	

	#########################################################################
	# System Preferences menu
	#########################################################################
	$module_handler = xoops_gethandler ( 'module' );
	$mod = & $module_handler->getByDirname ( 'system' );
	$menu = array ( );
	foreach ( $mod->getAdminMenu () as $lkn ) {
		$lkn ['dir'] = 'system';
		$menu [] = $lkn;
	}
	
	$admin_menu [$cont] ['id'] = 'opsystem';
	$admin_menu [$cont] ['text'] = _SYSTEM;
	$admin_menu [$cont] ['link'] = XOOPS_URL . '/modules/system/admin.php';
	$admin_menu [$cont] ['menu'] = $menu;
	$cont ++;
	#########################################################################
	# end
	#########################################################################
	

	#########################################################################
	# Modules menu
	#########################################################################
	$module_handler = & xoops_gethandler ( 'module' );
	$criteria = new CriteriaCompo ( );
	$criteria->add ( new Criteria ( 'hasadmin', 1 ) );
	$criteria->add ( new Criteria ( 'isactive', 1 ) );
	$criteria->setSort ( 'mid' );
	$modules = $module_handler->getObjects ( $criteria );
	foreach ( $modules as $module ) {
		$rtn = array ( );
		$inf = & $module->getInfo ();
		$rtn ['link'] = XOOPS_URL . '/modules/' . $module->dirname () . '/' . (isset ( $inf ['adminindex'] ) ? $inf ['adminindex'] : '');
		$rtn ['title'] = $module->name ();
		$rtn ['dir'] = $module->dirname ();
		if (isset ( $inf ['iconsmall'] ) && $inf ['iconsmall'] != '') {
			$rtn ['small'] = XOOPS_URL . '/modules/' . $module->dirname () . '/' . $inf ['iconsmall'];
		}
		if (isset ( $inf ['iconbig'] ) && $inf ['iconbig'] != '') {
			$rtn ['iconbig'] = XOOPS_URL . '/modules/' . $module->dirname () . '/' . $inf ['iconbig'];
		}
		$rtn ['absolute'] = 1;
		$module->loadAdminMenu ();
		if (is_array ( $module->adminmenu ) && count ( $module->adminmenu ) > 0) {
			$rtn ['hassubs'] = 1;
			$rtn ['subs'] = array ( );
			foreach ( $module->adminmenu as $item ) {
				$item ['link'] = XOOPS_URL . '/modules/' . $module->dirname () . '/' . $item ['link'];
				$rtn ['subs'] [] = $item;
			}
		} else {
			$rtn ['hassubs'] = 0;
			unset ( $rtn ['subs'] );
		}
		$hasconfig = $module->getVar ( 'hasconfig' );
		$hascomments = $module->getVar ( 'hascomments' );
		if ((isset ( $hasconfig ) && $hasconfig == 1) || (isset ( $hascomments ) && $hascomments == 1)) {
			$rtn ['hassubs'] = 1;
			if (! isset ( $rtn ['subs'] )) {
				$rtn ['subs'] = array ( );
			}
			$subs = array ('title' => _PREFERENCES, 'link' => XOOPS_URL . '/modules/system/admin.php?fct=preferences&op=showmod&mod=' . $module->mid () );
			$rtn ['subs'] [] = $subs;
		} else {
			$rtn ['hassubs'] = 0;
			unset ( $rtn ['subs'] );
		}
		if ($module->dirname () == 'system') {
			$systemadm = true;
		}
		$modules_menu [] = $rtn;
	}
	
	$admin_menu [$cont] ['id'] = 'modules';
	$admin_menu [$cont] ['text'] = _MODULES;
	$admin_menu [$cont] ['link'] = XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin';
	$admin_menu [$cont] ['menu'] = $modules_menu;
	$cont ++;
	#########################################################################
	# end
	#########################################################################
	

	#########################################################################
	# ImpressCMS News Feed menu
	#########################################################################
	$i = 0;
	$menu = array ( );
	
	$menu [$i] ['link'] = 'http://www.impresscms.org';
	$menu [$i] ['title'] = _IMPRESSCMS_HOME;
	$menu [$i] ['absolute'] = 1;
	//$menu[$i]['small'] = XOOPS_URL.'/images/impresscms.png';
	$i ++;
	
	$menu [$i] ['link'] = 'http://community.impresscms.org';
	$menu [$i] ['title'] = _IMPRESSCMS_COMMUNITY;
	$menu [$i] ['absolute'] = 1;
	//$menu[$i]['small'] = XOOPS_URL.'/images/impresscms.png';
	$i ++;
	
	$menu [$i] ['link'] = 'http://addons.impresscms.org';
	$menu [$i] ['title'] = _IMPRESSCMS_ADDONS;
	$menu [$i] ['absolute'] = 1;
	//$menu[$i]['small'] = XOOPS_URL.'/images/impresscms.png';
	$i ++;
	
	$menu [$i] ['link'] = 'http://wiki.impresscms.org';
	$menu [$i] ['title'] = _IMPRESSCMS_WIKI;
	$menu [$i] ['absolute'] = 1;
	//$menu[$i]['small'] = XOOPS_URL.'/images/impresscms.png';
	$i ++;
	
	$menu [$i] ['link'] = 'http://blog.impresscms.org';
	$menu [$i] ['title'] = _IMPRESSCMS_BLOG;
	$menu [$i] ['absolute'] = 1;
	//$menu[$i]['small'] = XOOPS_URL.'/images/impresscms.png';
	$i ++;
	
	$menu [$i] ['link'] = 'http://sourceforge.net/projects/impresscms/';
	$menu [$i] ['title'] = _IMPRESSCMS_SOURCEFORGE;
	$menu [$i] ['absolute'] = 1;
	//$menu[$i]['small'] = XOOPS_URL.'/images/impresscms.png';
	$i ++;
	
	$menu [$i] ['link'] = 'http://www.impresscms.org/donations/';
	$menu [$i] ['title'] = _IMPRESSCMS_DONATE;
	$menu [$i] ['absolute'] = 1;
	//$menu[$i]['small'] = XOOPS_URL.'/images/impresscms.png';
	$i ++;
	
	$menu [$i] ['link'] = XOOPS_URL . "/admin.php?rssnews=1";
	$menu [$i] ['title'] = _IMPRESSCMS_NEWS;
	$menu [$i] ['absolute'] = 1;
	//$menu[$i]['small'] = XOOPS_URL.'/images/impresscms.png';
	$i ++;
	
	$admin_menu [$cont] ['id'] = 'news';
	$admin_menu [$cont] ['text'] = _ABOUT;
	$admin_menu [$cont] ['link'] = '#';
	$admin_menu [$cont] ['menu'] = $menu;
	$cont ++;
	#########################################################################
	# end
	#########################################################################
	

	return $admin_menu;
}

/**
 * Function maintained only for compatibility
 *
 * @todo Search all places that this function is called
 *       and rename it to impresscms_get_adminmenu.
 *       After this function can be removed.
 *
 */
function xoops_module_get_admin_menu() {
	return impresscms_get_adminmenu ();
}

function xoops_module_write_admin_menu($content) {
	global $xoopsConfig;
	if (! xoopsfwrite ()) {
		return false;
	}
	
	$filename = XOOPS_CACHE_PATH . '/adminmenu_' . $xoopsConfig ['language'] . '.php';
	if (! $file = fopen ( $filename, "w" )) {
		echo 'failed open file';
		return false;
	}
	if (fwrite ( $file, var_export ( $content, true ) ) == - 1) {
		echo 'failed write file';
		return false;
	}
	fclose ( $file );
	
	// write index.html file in cache folder
	// file is delete after clear_cache (smarty)
	xoops_write_index_file ( XOOPS_CACHE_PATH );
	return true;
}

function xoops_write_index_file($path = '') {
	if (empty ( $path )) {
		return false;
	}
	if (! xoopsfwrite ()) {
		return false;
	}
	
	$path = substr ( $path, - 1 ) == "/" ? substr ( $path, 0, - 1 ) : $path;
	$filename = $path . '/index.html';
	if (file_exists ( $filename )) {
		return true;
	}
	if (! $file = fopen ( $filename, "w" )) {
		echo 'failed open file';
		return false;
	}
	if (fwrite ( $file, "<script>history.go(-1);</script>" ) == - 1) {
		echo 'failed write file';
		return false;
	}
	fclose ( $file );
	return true;
}
?>