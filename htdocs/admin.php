<?php
/**
* Admin control panel entry page
*
* This page is responsible for
* - displaying the home of the Control Panel
* - checking for cache/adminmenu.php
* - displaying RSS feed of the ImpressCMS Project
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		core
* @since		XOOPS
* @author		http://www.xoops.org The XOOPS Project
* @author		modified by marcan <marcan@impresscms.org>
* @version		$Id: admin.php 8006 2009-01-28 17:04:55Z malanciault $
*/

$xoopsOption['pagetype'] = 'admin';
include 'mainfile.php';
include ICMS_ROOT_PATH.'/include/cp_functions.php';

// Admin Authentication
if($xoopsUser)
{
	if(!$xoopsUser->isAdmin(-1)) {redirect_header('index.php',2,_AD_NORIGHT);}
}
else {redirect_header('index.php',2,_AD_NORIGHT);}
// end Admin Authentication

$op = isset($_GET['rssnews']) ? intval($_GET['rssnews']) : 0;
if(!empty($_GET['op'])) {$op = intval($_GET['op']);}
if(!empty($_POST['op'])) {$op = intval($_POST['op']);}

if(!file_exists(ICMS_CACHE_PATH.'/adminmenu_'.$xoopsConfig['language'].'.php') && $op != 2)
{
	xoops_header();
	xoops_confirm(array('op' => 2), 'admin.php', _RECREATE_ADMINMENU_FILE);
	xoops_footer();
	exit();
}

switch($op)
{
	case 1:
		xoops_cp_header();
		showRSS();
	break;
	case 2:
		xoops_module_write_admin_menu(impresscms_get_adminmenu());
		redirect_header('javascript:history.go(-1)', 1, _AD_LOGINADMIN);
	break;

	default:
		$mods = xoops_cp_header(1);

		// ###### Output warn messages for security  ######
		if(is_dir(ICMS_ROOT_PATH.'/install/'))
		{
			xoops_error(sprintf(_WARNINSTALL2,ICMS_ROOT_PATH.'/install/'));
			echo '<br />';
		}
		$db = $GLOBALS['xoopsDB'];
		if(getDbValue($db, 'modules', 'version', 'version="120"') == 0 AND getDbValue($db, 'modules', 'mid', 'mid="1"') == 1)
		{
			xoops_error('<a href="'.ICMS_URL.'/modules/system/admin.php?fct=modulesadmin&op=update&module=system">'._WARNINGUPDATESYSTEM.'</a>');
			echo '<br />';
		}
		if(is_writable(ICMS_ROOT_PATH.'/mainfile.php'))
		{
			xoops_error(sprintf(_WARNINWRITEABLE,ICMS_ROOT_PATH.'/mainfile.php'));
			echo '<br />';
		}
		if(is_dir(ICMS_ROOT_PATH.'/upgrade/'))
		{
			xoops_error(sprintf(_WARNINSTALL2,ICMS_ROOT_PATH.'/upgrade/'));
			echo '<br />';
		}
		$sql1 = "SELECT conf_modid FROM `".$xoopsDB->prefix('config')."` WHERE conf_name = 'dos_skipmodules'";
		if($result1 = $xoopsDB->query($sql1))
		{
			list($modid) = $xoopsDB->FetchRow($result1);
			$protector_is_active = '0';
			if (!is_null($modid)){
			$sql2 = "SELECT isactive FROM `".$xoopsDB->prefix('modules')."` WHERE mid =".$modid;
			$result2 = $xoopsDB->query($sql2);
			list($protector_is_active) = $xoopsDB->FetchRow($result2);
			}
		}
		if($protector_is_active == 0)
		{
			xoops_error(_PROTECTOR_NOT_FOUND);
			echo '<br />';
		}
		// ###### Output warn messages for correct functionality  ######
		if(!is_writable(ICMS_CACHE_PATH))
		{
			xoops_warning(sprintf(_WARNINNOTWRITEABLE,ICMS_CACHE_PATH));
			echo '<br />';
		}
		if(!is_writable(ICMS_UPLOAD_PATH))
		{
			xoops_warning(sprintf(_WARNINNOTWRITEABLE,ICMS_UPLOAD_PATH));
			echo '<br />';
		}
		if(!is_writable(ICMS_COMPILE_PATH))
		{
			xoops_warning(sprintf(_WARNINNOTWRITEABLE,ICMS_COMPILE_PATH));
			echo '<br />';
		}

		$icmsAdminTpl->assign('lang_cp', _CPHOME);
		$icmsAdminTpl->assign('lang_insmodules', _AD_INSTALLEDMODULES);
		// Loading allowed Modules
		$icmsAdminTpl->assign('modules', $mods);

		if(count($mods) > 0) {$icmsAdminTpl->assign('modulesadm', 1);}
		else {$icmsAdminTpl->assign('modulesadm', 0);}

		// Loading System Configuration Links
		$groups = $xoopsUser->getGroups();
		$all_ok = false;
		if(!in_array(XOOPS_GROUP_ADMIN, $groups))
		{
			$sysperm_handler =& xoops_gethandler('groupperm');
			$ok_syscats =& $sysperm_handler->getItemIds('system_admin', $groups);
		}
		else {$all_ok = true;}

		require_once ICMS_ROOT_PATH.'/class/xoopslists.php';
		require_once ICMS_ROOT_PATH.'/modules/system/constants.php';

		$admin_dir = ICMS_ROOT_PATH.'/modules/system/admin';
		$dirlist = XoopsLists::getDirListAsArray($admin_dir);

		if(file_exists(ICMS_ROOT_PATH.'/modules/system/language/'.$xoopsConfig['language'].'/admin.php'))
		{
			include ICMS_ROOT_PATH.'/modules/system/language/'.$xoopsConfig['language'].'/admin.php';
		}
		elseif(file_exists(ICMS_ROOT_PATH.'/modules/system/language/english/admin.php'))
		{
			include ICMS_ROOT_PATH.'/modules/system/language/english/admin.php';
		}

		$cont = 0;
		asort($dirlist);
		foreach($dirlist as $file)
		{
			include $admin_dir.'/'.$file.'/xoops_version.php';
			if($modversion['hasAdmin'])
			{
				$category = isset($modversion['category']) ? intval($modversion['category']) : 0;
				if(false != $all_ok || in_array($modversion['category'], $ok_syscats))
				{
					$sysmod = array('title' => $modversion['name'], 'link' => ICMS_URL.'/modules/system/admin.php?fct='.$file, 'image' => ICMS_URL.'/modules/system/admin/'.$file.'/images/'.$file.'_big.png');
					$icmsAdminTpl->append('sysmod', $sysmod);
					$cont++;
				}
			}
			unset($modversion);
		}
		if($cont > 0) {$icmsAdminTpl->assign('systemadm', 1);}
		else {$icmsAdminTpl->assign('systemadm', 0);}

		echo $icmsAdminTpl->fetch(ICMS_ROOT_PATH.'/modules/system/templates/admin/system_indexcp.html');
	break;
}

function showRSS()
{
	global $icmsAdminTpl;

	$config_handler =& xoops_gethandler('config');
	$xoopsConfigPersona =& $config_handler->getConfigsByCat(XOOPS_CONF_PERSONA);
	$rssurl = $xoopsConfigPersona['rss_local'];
	$rssfile = ICMS_CACHE_PATH.'/adminnews_'._LANGCODE.'.xml';

	include_once(ICMS_ROOT_PATH . '/class/icmssimplerss.php');

	// Create a new instance of the SimplePie object
	$feed = new IcmsSimpleRss();
	$feed->set_feed_url($rssurl);
	$feed->set_cache_duration(3600);
	$feed->set_autodiscovery_level(SIMPLEPIE_LOCATOR_NONE);
	$feed->init();
	$feed->handle_content_type();

	if (!$feed->error) {
		$icmsAdminTpl->assign('admin_rss_feed_link', $feed->get_link());
		$icmsAdminTpl->assign('admin_rss_feed_title', $feed->get_title());
		$icmsAdminTpl->assign('admin_rss_feed_dsc', $feed->get_description());
		$feeditems = array();
		foreach($feed->get_items() as $item) {
			$feeditem = array();
			$feeditem['link'] = $item->get_permalink();
			$feeditem['title'] = $item->get_title();
			$feeditem['description'] = $item->get_description();
			$feeditem['date'] = $item->get_date();
			$feeditem['guid'] = $item->get_id();
			$feeditems[] = $feeditem;
		}
		$icmsAdminTpl->assign('admin_rss_feeditems', $feeditems);
	}

	$icmsAdminTpl->display('db:admin/system_adm_rss.html');
}
xoops_cp_footer();
?>