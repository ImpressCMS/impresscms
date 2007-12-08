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
define('XOOPS_CPFUNC_LOADED', 1);

/**
 * Creamos la variable para uso de Smarty
 */
include_once XOOPS_ROOT_PATH.'/class/template.php';
$tpl = new XoopsTpl();
$tpl->assign('xoops_url',XOOPS_URL);
$tpl->assign('xoops_sitename',$xoopsConfig['sitename']);

function xoops_cp_header($ret = 0)
{
    global $xoopsConfig, $xoopsModule, $xoopsUser, $tpl;
	if ($xoopsConfig['gzip_compression'] == 1) {
		ob_start("ob_gzhandler");
	} else {
		ob_start();
	}
	if (!headers_sent()) {
		header('Content-Type:text/html; charset='._CHARSET);
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
    }
	echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>";
	echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'._LANGCODE.'" lang="'._LANGCODE.'">
	<head>
	<meta http-equiv="content-type" content="text/html; charset='._CHARSET.'" />
	<meta http-equiv="content-language" content="'._LANGCODE.'" />
	<title>'.htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES).' Administration</title>
	<script type="text/javascript" src="'.XOOPS_URL.'/include/xoops.js"></script>' .
	'<link rel="shortcut icon" type="image/ico" href="'.XOOPS_URL.'/favicon.ico" />
	<link rel="icon" type="image/png" href="'.XOOPS_URL.'/favicon.ico" />
	';
	echo '<link rel="stylesheet" type="text/css" media="all" href="'.XOOPS_URL.'/xoops.css" />';
        echo '<link rel="stylesheet" type="text/css" media="all" href="'.XOOPS_URL.'/modules/system/style.css" />';
        //include_once XOOPS_CACHE_PATH.'/adminmenu.php';


        //$logo = file_exists(XOOPS_THEME_URL."/".getTheme()."/images/logo.gif") ? XOOPS_THEME_URL."/".getTheme()."/images/logo.gif" : XOOPS_URL."/images/logo.gif";
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

//--><!]]></script>
	</head>
        <body>" ;

	/**
	 * Loading current options of the module.
	 */
	if ($xoopsModule){
		$module_admin_menu = $xoopsModule->getAdminMenu();
		for ($i=count($module_admin_menu)-1;$i>=0;$i=$i-1) {
			$reversed_module_admin_menu[] = $module_admin_menu[$i];
		}
		foreach ($reversed_module_admin_menu as $k){
			$tpl->append('mod_options', array('title'=>$k['title'], 'link'=>$k['link'], 'icon'=>(isset($k['icon']) && $k['icon']!='' ? $k['icon'] : '')));
		}
		$tpl->assign('modpath', XOOPS_URL . '/modules/' . $xoopsModule->dirname());
		$tpl->assign('modname', $xoopsModule->name());
		$tpl->assign('modid', $xoopsModule->mid());
		$tpl->assign('moddir', $xoopsModule->dirname());
	}
	/**
	 * Loading navigation bar
	 */

	include_once(XOOPS_ROOT_PATH."/class/xoopslists.php");
	$languages = XoopsLists::getLangList();
	global $xoops_language_tags;

	$i = 0;

	$menu[$i]['link'] = XOOPS_URL."/admin.php";
	$menu[$i]['title'] = _CPHOME;
	$menu[$i]['absolute'] = 1;
	$menu[$i]['small'] = XOOPS_URL."/modules/system/images/mini_cp.png";
	$i++;

	$menu[$i]['link'] = XOOPS_URL;
	$menu[$i]['title'] = _YOURHOME;
	$menu[$i]['absolute'] = 1;
	$menu[$i]['small'] = XOOPS_URL."/modules/system/images/home.png";
	$i++;

	foreach($languages as $language) {
		if ($xoopsConfig['language'] != $language) {
			$language_name = isset($xoops_language_tags[$language]) ? $xoops_language_tags[$language]->caption() : $language;
			$menu[$i]['title'] = _SWITCH_TO . '&nbsp;' . $language_name;
			$menu[$i]['link'] = XOOPS_URL . '/admin.php?sel_lang=' . $language;
			$menu[$i]['absolute'] = 1;
			$i++;
		}
	}

	$menu[$i]['link'] = XOOPS_URL."/user.php?op=logout";
	$menu[$i]['title'] = _LOGOUT;
	$menu[$i]['absolute'] = 1;
	$menu[$i]['small'] = XOOPS_URL.'/images/logout.png';

	$tpl->append('navitems', array('link'=>'#','text'=>_CPHOME, 'menu'=>$menu));

	$module_handler = xoops_gethandler('module');
	$mod =& $module_handler->getByDirname('system');
	$moduleperm_handler =& xoops_gethandler('groupperm');
    $sadmin = $moduleperm_handler->checkRight('module_admin', $mod->mid(), $xoopsUser->getGroups());
	$tpl->append('navitems', array('link'=>XOOPS_URL.'/modules/system/admin.php', 'text'=>_SYSTEM, 'dir'=>'system', 'menu'=>$mod->getAdminMenu()));

	$module_handler =& xoops_gethandler('module');
	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('hasadmin', 1));
	$criteria->add(new Criteria('isactive', 1));
	$criteria->setSort('mid');
	$mods = $module_handler->getObjects($criteria);

	$menu = array();
	foreach ($mods as $m){
		$rtn = array();
		$moduleperm_handler =& xoops_gethandler('groupperm');
		$sadmin = $moduleperm_handler->checkRight('module_admin', $m->mid(), $xoopsUser->getGroups());
		if ($sadmin){
			$info =& $m->getInfo();
			$rtn['link'] = XOOPS_URL . '/modules/'. $m->dirname() . '/' . $info['adminindex'];
			$rtn['title'] = $m->name();
			$rtn['absolute'] = 1;
			if (isset($info['iconsmall']) && $info['iconsmall']!='' ) $rtn['small'] = XOOPS_URL . '/modules/' . $m->dirname() . '/' . $info['iconsmall'];
		}
		$menu[] = $rtn;
	}

	$tpl->append('navitems', array('link'=>XOOPS_URL.'/modules/system/admin.php?fct=modulesadmin', 'text'=>_MODULES, 'dir'=>$m->dirname(), 'menu'=>$menu));

	$i=0;

	$menu = array();

	$menu[$i]['link'] = XOOPS_URL."/admin.php?rssnews=1";
	$menu[$i]['title'] = "XOOPS.org";
	$menu[$i]['absolute'] = 1;
	$menu[$i]['small'] = XOOPS_URL.'/images/xoops.png';
	$i++;


	$tpl->append('navitems', array('link'=>"#",'text'=>_AD_NEWS, 'menu'=>$menu));

	$tpl->assign('lang_prefs', _XMEX_PREFS);

	echo $tpl->fetch(XOOPS_ROOT_PATH.'/modules/system/templates/admin/system_adm_navbar.html');
	echo "<div id='containBodyCP'><br /><div id='bodyCP'>";

	if ($ret) return $mods;
}

function xoops_cp_footer()
{
	global $xoopsConfig, $xoopsLogger;
	echo"</div><br /></div>";
	echo "<div class='CPfoot'>Powered by&nbsp;".XOOPS_VERSION." &copy; 2001-".date("Y")." <a href='http://www.xoops.org/' target='_blank'>ImpressCMS</a></div>
        </body>
      </html>
    ";
	echo $GLOBALS['xoopsLogger']->render( '' );
	ob_end_flush();
}

// We need these because theme files will not be included
function OpenTable()
{
	echo "<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 2px solid #2F5376;'><tr class='bg4'><td valign='top'>\n";
}

function CloseTable()
{
	echo '</td></tr></table>';
}

function themecenterposts($title, $content)
{
	echo '<table cellpadding="4" cellspacing="1" width="98%" class="outer"><tr><td class="head">'.$title.'</td></tr><tr><td><br />'.$content.'<br /></td></tr></table>';
}

function myTextForm($url , $value)
{
	return '<form action="'.$url.'" method="post"><input type="submit" value="'.$value.'" /></form>';
}

function xoopsfwrite()
{
	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
		return false;
	} else {

    }
	if (!xoops_refcheck()) {
		return false;
	} else {

	}
    return true;
}

function xoops_module_get_admin_menu()
{
    // En blanco, se mantiene por compatibilidad
}

function xoops_module_write_admin_menu($content)
{
    if (!xoopsfwrite()) {
        return false;
    }
    $filename = XOOPS_CACHE_PATH.'/adminmenu.php';
    if ( !$file = fopen($filename, "w") ) {
        echo 'failed open file';
        return false;
    }
    if ( fwrite($file, $content) == -1 ) {
        echo 'failed write file';
        return false;
    }
    fclose($file);
    return true;
}
?>