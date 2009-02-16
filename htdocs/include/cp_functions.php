<?php
/**
* All control panel functions and forming goes from here.
* Be careful while editing this file!
*
* @copyright	XOOPS_copyrights.txt
* @copyright	The XOOPS Project <http://www.xoops.org/>
* @copyright	The ImpressCMS Project <http://www.impresscms.org/>
* 
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* 
* @package		core
* @since		XOOPS
* @version		$Id$
* 
* @author		The XOOPS Project <http://www.xoops.org>
* @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
* @author 		Gustavo Pilla (aka nekro) <nekro@impresscms.org>
*/
define ( 'XOOPS_CPFUNC_LOADED', 1 );

include_once ICMS_ROOT_PATH . '/class/template.php';

/**
 * Function icms_cp_header
 * 
 * @since ImpressCMS 1.2
 * @version $Id$
 * 
 * @author rowd (from the XOOPS Community)
 * @author nekro (aka Gustavo Pilla)<nekro@impresscms.org>
 */
function icms_cp_header(){
    global $xoopsConfig, $xoopsOption, $xoTheme, $xoopsLogger, $icmsAdminTpl;
	
	$xoopsLogger->stopTime( 'Module init' );
	$xoopsLogger->startTime( 'ImpressCMS CP Output Init' );
	
	if (!headers_sent()) {
		header('Content-Type:text/html; charset='._CHARSET);
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
    }
	
    require_once ICMS_ROOT_PATH . '/class/template.php';
    require_once ICMS_ROOT_PATH . '/class/theme.php';
    require_once ICMS_ROOT_PATH . '/class/theme_blocks.php';
	
    
	$icmsAdminTpl = new XoopsTpl();
	
	$icmsAdminTpl->assign('xoops_url',ICMS_URL);
	$icmsAdminTpl->assign('xoops_sitename',$xoopsConfig['sitename']);
	
	if ( @$xoopsOption['template_main'] ) {
		if ( false === strpos( $xoopsOption['template_main'], ':' ) ) {
			$xoopsOption['template_main'] = 'db:' . $xoopsOption['template_main'];
		}
	}
	$xoopsThemeFactory = new xos_opal_ThemeFactory();
	$xoopsThemeFactory->allowedThemes = $xoopsConfig['theme_set_allowed'];
	
	// The next 2 lines are for compatibility only... to implement the admin theme ;)
	// TODO: Remove all this after a few versions!!
	if(isset($xoopsConfig['theme_admin_set']))
		$xoopsThemeFactory->defaultTheme = $xoopsConfig['theme_admin_set'];
    $xoTheme =& $xoopsThemeFactory->createInstance( array(
    	'contentTemplate'	=> @$xoopsOption['template_main'],
    	'canvasTemplate'	=> 'theme'.(( file_exists(ICMS_THEME_PATH.'/'.$xoopsConfig['theme_admin_set'].'/theme_admin.html')) ?'_admin':'').'.html',
    	'plugins' 			=> array('xos_logos_PageBuilder')
    ) );
    $icmsAdminTpl = $xoTheme->template;
    
	$xoTheme->addScript( ICMS_URL.'/include/xoops.js', array( 'type' => 'text/javascript' ) );
	$xoTheme->addScript( '' ,array( 'type' => 'text/javascript' ) , 'startList = function() {
	if (document.all&&document.getElementById) {
		navRoot = document.getElementById("nav");
		for (i=0; i<navRoot.childNodes.length; i++) {
			node = navRoot.childNodes[i];
			if (node.nodeName=="LI") {
				node.onmouseover=function() {
					this.className+=" over";
				}
				node.onmouseout=function() {
					this.className=this.className.replace(" over", "");
				}
			}
		}
	}
}
window.onload=startList;');
    $xoTheme->addStylesheet(ICMS_URL.'/icms'.(( defined('_ADM_USE_RTL') && _ADM_USE_RTL )?'_rtl':'').'.css', array('media' => 'screen'));
//	$xoTheme->addStylesheet( '/modules/system/style.css' );
	
			$config_handler =& xoops_gethandler('config');
			$icmsConfigPlugins =& $config_handler->getConfigsByCat(ICMS_CONF_PLUGINS);
 			$jscript = '';
 		        foreach ($icmsConfigPlugins['sanitizer_plugins'] as $key) {
 		        	if(empty($key)) continue;
 		        	if(file_exists(ICMS_ROOT_PATH.'/plugins/textsanitizer/'.$key.'/'.$key.'.js')){
 		        		$xoTheme->addScript(ICMS_URL.'/plugins/textsanitizer/'.$key.'/'.$key.'.js', array('type' => 'text/javascript'));
 		        	}else{
 		        		$extension = include_once ICMS_ROOT_PATH.'/plugins/textsanitizer/'.$key.'/'.$key.'.php';
 		        		$func = 'javascript_'.$key;
 		        		if ( function_exists($func) ) {
 		        			@list($encode, $jscript) = $func($ele_name);
 		        		 	if (!empty($jscript)) {
 		        		 		if(!file_exists(ICMS_ROOT_PATH.'/'.$jscript)){
 		        					$xoTheme->addScript('', array('type' => 'text/javascript'), $jscript);
 		        				}else{
 		        					$xoTheme->addScript($jscript, array('type' => 'text/javascript'));
 		        				}
 		        			}
 		        		}
 		        	}
 		        }

 			$style_info = '';
 		        foreach ($icmsConfigPlugins['sanitizer_plugins'] as $key) {
 		        	if(empty($key)) continue;
 		        	if(file_exists(ICMS_ROOT_PATH.'/plugins/textsanitizer/'.$key.'/'.$key.'.css')){
 		        		$xoTheme->addStylesheet(ICMS_URL.'/plugins/textsanitizer/'.$key.'/'.$key.'.css', array('media' => 'screen'));
 		        	}else{
 		        		$extension = include_once ICMS_ROOT_PATH.'/plugins/textsanitizer/'.$key.'/'.$key.'.php';
 		        		$func = 'stlye_'.$key;
 		        		if ( function_exists($func) ) {
 		        			$style_info = $func();
 		        		 	if (!empty($style_info)) {
 		        		 		if(!file_exists(ICMS_ROOT_PATH.'/'.$style_info)){
 		        					$xoTheme->addStylesheet('', array('media' => 'screen'), $style_info);
 		        				}else{
 		        					$xoTheme->addStylesheet($style_info, array('media' => 'screen'));
 		        				}
 		        			}
 		        		}
 		        	}
 		        }

	if ( @is_object( $xoTheme->plugins['xos_logos_PageBuilder'] ) ) {
		$aggreg =& $xoTheme->plugins['xos_logos_PageBuilder'];

	    $icmsAdminTpl->assign_by_ref( 'xoAdminBlocks', $aggreg->blocks );

	    // Backward compatibility code for pre 2.0.14 themes
		$icmsAdminTpl->assign_by_ref( 'xoops_lblocks', $aggreg->blocks['canvas_left'] );
		$icmsAdminTpl->assign_by_ref( 'xoops_rblocks', $aggreg->blocks['canvas_right'] );
		$icmsAdminTpl->assign_by_ref( 'xoops_ccblocks', $aggreg->blocks['page_topcenter'] );
		$icmsAdminTpl->assign_by_ref( 'xoops_clblocks', $aggreg->blocks['page_topleft'] );
		$icmsAdminTpl->assign_by_ref( 'xoops_crblocks', $aggreg->blocks['page_topright'] );

		$icmsAdminTpl->assign( 'xoops_showlblock', !empty($aggreg->blocks['canvas_left']) );
		$icmsAdminTpl->assign( 'xoops_showrblock', !empty($aggreg->blocks['canvas_right']) );
		$icmsAdminTpl->assign( 'xoops_showcblock', !empty($aggreg->blocks['page_topcenter']) || !empty($aggreg->blocks['page_topleft']) || !empty($aggreg->blocks['page_topright']) );
		
		$config_handler = & xoops_gethandler ( 'config' );
		$xoopsConfigPersona = & $config_handler->getConfigsByCat( XOOPS_CONF_PERSONA );
		$icmsAdminTpl->assign( 'adm_left_logo', $xoopsConfigPersona['adm_left_logo'] );		
		$icmsAdminTpl->assign( 'adm_left_logo_url', $xoopsConfigPersona['adm_left_logo_url'] );
		$icmsAdminTpl->assign( 'adm_left_logo_alt', $xoopsConfigPersona['adm_left_logo_alt'] );
		$icmsAdminTpl->assign( 'adm_right_logo', $xoopsConfigPersona['adm_right_logo'] );
		$icmsAdminTpl->assign( 'adm_right_logo_url', $xoopsConfigPersona['adm_right_logo_url'] );
		$icmsAdminTpl->assign( 'adm_right_logo_alt', $xoopsConfigPersona['adm_right_logo_alt'] );
		$icmsAdminTpl->assign( 'show_impresscms_menu', $xoopsConfigPersona['show_impresscms_menu']);
		
	}
}

/**
 * Backwards compatibility function.
 * 
 * @since XOOPS
 * @version $Id$
 * @deprecated 
 *
 * @author The Xoops Project <http://www.xoops.org>
 * @author Gustavo Pilla (aka nekro) <nekro@impresscms.org> 
 */
function xoops_cp_header(){
	icms_cp_header();
}

/**
 * Function icms_cp_footer
 * 
 * @since ImpressCMS 1.2
 * @version $Id$
 * @author rowd (from XOOPS Community)
 * @author Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 */
function icms_cp_footer(){
	global $xoopsConfig, $xoopsOption, $xoopsLogger, $xoopsUser, $xoTheme, $icmsTpl ,$im_multilanguageConfig, $icmsLibrariesHandler, $xoopsModule;
	$xoopsLogger->stopTime( 'Module display' );

	if (!headers_sent()) {
			header('Content-Type:text/html; charset='._CHARSET);
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Cache-Control: private, no-cache');
			header('Pragma: no-cache');
	}
	if ( isset( $xoopsOption['template_main'] ) && $xoopsOption['template_main'] != $xoTheme->contentTemplate ) {
		trigger_error( "xoopsOption[template_main] should be defined before including header.php", E_USER_WARNING );
		if ( false === strpos( $xoopsOption['template_main'], ':' ) ) {
			$xoTheme->contentTemplate = 'db:' . $xoopsOption['template_main'];
		} else {
			$xoTheme->contentTemplate = $xoopsOption['template_main'];
		}
	}
		
	$xoopsLogger->stopTime( 'XOOPS output init' );
	$xoopsLogger->startTime( 'Module display' );
	
	$xoTheme->render();

	$xoopsLogger->stopTime();
	return;
}

/**
 * Backwars compatibility function
 * 
 * @version $Id$
 * @deprecated
 * 
 * @author The XOOPS Project <http://www.xoops.org>
 * @author Gustavo Pilla (aka nekro) <nekro@impresscms.org> 
 */
function xoops_cp_footer(){
	icms_cp_footer();	
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
 * @since ImpressCMS 1.1
 * @version $Id$
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
	$menu [$i] ['link'] = ICMS_URL . "/admin.php";
	$menu [$i] ['title'] = _CPHOME;
	$menu [$i] ['absolute'] = 1;
	$menu [$i] ['small'] = ICMS_URL . "/modules/system/images/mini_cp.png";
	$i ++;
	
	$menu [$i] ['link'] = ICMS_URL;
	$menu [$i] ['title'] = _YOURHOME;
	$menu [$i] ['absolute'] = 1;
	$menu [$i] ['small'] = ICMS_URL . "/modules/system/images/home.png";
	$i ++;
	
	$menu [$i] ['link'] = ICMS_URL . "/user.php?op=logout";
	$menu [$i] ['title'] = _LOGOUT;
	$menu [$i] ['absolute'] = 1;
	$menu [$i] ['small'] = ICMS_URL . '/images/logout.png';
	
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
	$admin_menu [$cont] ['link'] = ICMS_URL . '/modules/system/admin.php';
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
		$rtn ['link'] = ICMS_URL . '/modules/' . $module->dirname () . '/' . (isset ( $inf ['adminindex'] ) ? $inf ['adminindex'] : '');
		$rtn ['title'] = $module->name ();
		$rtn ['dir'] = $module->dirname ();
		if (isset ( $inf ['iconsmall'] ) && $inf ['iconsmall'] != '') {
			$rtn ['small'] = ICMS_URL . '/modules/' . $module->dirname () . '/' . $inf ['iconsmall'];
		}
		if (isset ( $inf ['iconbig'] ) && $inf ['iconbig'] != '') {
			$rtn ['iconbig'] = ICMS_URL . '/modules/' . $module->dirname () . '/' . $inf ['iconbig'];
		}
		$rtn ['absolute'] = 1;
		$module->loadAdminMenu ();
		if (is_array ( $module->adminmenu ) && count ( $module->adminmenu ) > 0) {
			$rtn ['hassubs'] = 1;
			$rtn ['subs'] = array ( );
			foreach ( $module->adminmenu as $item ) {
				$item ['link'] = ICMS_URL . '/modules/' . $module->dirname () . '/' . $item ['link'];
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
			$subs = array ('title' => _PREFERENCES, 'link' => ICMS_URL . '/modules/system/admin.php?fct=preferences&op=showmod&mod=' . $module->mid () );
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
	$admin_menu [$cont] ['link'] = ICMS_URL . '/modules/system/admin.php?fct=modulesadmin';
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
	//$menu[$i]['small'] = ICMS_URL.'/images/impresscms.png';
	$i ++;
	
	if ( _LANGCODE != 'en' ){
	$menu [$i] ['link'] = _IMPRESSCMS_LOCAL_SUPPORT;
	$menu [$i] ['title'] = _IMPRESSCMS_LOCAL_SUPPORT_TITLE;
	$menu [$i] ['absolute'] = 1;
	//$menu[$i]['small'] = ICMS_URL.'/images/impresscms.png';
	$i ++;
    }

	$menu [$i] ['link'] = 'http://community.impresscms.org';
	$menu [$i] ['title'] = _IMPRESSCMS_COMMUNITY;
	$menu [$i] ['absolute'] = 1;
	//$menu[$i]['small'] = ICMS_URL.'/images/impresscms.png';
	$i ++;
	
	$menu [$i] ['link'] = 'http://addons.impresscms.org';
	$menu [$i] ['title'] = _IMPRESSCMS_ADDONS;
	$menu [$i] ['absolute'] = 1;
	//$menu[$i]['small'] = ICMS_URL.'/images/impresscms.png';
	$i ++;
	
	$menu [$i] ['link'] = 'http://wiki.impresscms.org';
	$menu [$i] ['title'] = _IMPRESSCMS_WIKI;
	$menu [$i] ['absolute'] = 1;
	//$menu[$i]['small'] = ICMS_URL.'/images/impresscms.png';
	$i ++;
	
	$menu [$i] ['link'] = 'http://blog.impresscms.org';
	$menu [$i] ['title'] = _IMPRESSCMS_BLOG;
	$menu [$i] ['absolute'] = 1;
	//$menu[$i]['small'] = ICMS_URL.'/images/impresscms.png';
	$i ++;
	
	$menu [$i] ['link'] = 'http://sourceforge.net/projects/impresscms/';
	$menu [$i] ['title'] = _IMPRESSCMS_SOURCEFORGE;
	$menu [$i] ['absolute'] = 1;
	//$menu[$i]['small'] = ICMS_URL.'/images/impresscms.png';
	$i ++;
	
	$menu [$i] ['link'] = 'http://www.impresscms.org/donations/';
	$menu [$i] ['title'] = _IMPRESSCMS_DONATE;
	$menu [$i] ['absolute'] = 1;
	//$menu[$i]['small'] = ICMS_URL.'/images/impresscms.png';
	$i ++;
	
	$menu [$i] ['link'] = ICMS_URL . "/admin.php?rssnews=1";
	$menu [$i] ['title'] = _IMPRESSCMS_NEWS;
	$menu [$i] ['absolute'] = 1;
	//$menu[$i]['small'] = ICMS_URL.'/images/impresscms.png';
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
	
	$filename = ICMS_CACHE_PATH . '/adminmenu_' . $xoopsConfig ['language'] . '.php';
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
	xoops_write_index_file ( ICMS_CACHE_PATH );
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