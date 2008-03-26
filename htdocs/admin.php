<?php
// $Id$
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

$xoopsOption['pagetype'] = "admin";
include "mainfile.php";
include XOOPS_ROOT_PATH."/include/cp_functions.php";

/*********************************************************/
/* Admin Authentication                                  */
/*********************************************************/
if ( $xoopsUser ) {
	if ( !$xoopsUser->isAdmin(-1) ) {
		redirect_header("index.php",2,_AD_NORIGHT);
		exit();
	}
} else {
	redirect_header("index.php",2,_AD_NORIGHT);
	exit();
}
/*********************************************************/
/* end					                                 */
/*********************************************************/

$op = isset($_GET['rssnews']) ? $_GET['rssnews'] : 0;
if ( !empty($_GET['op']) ) {
	$op = $_GET['op'];
}

if ( !empty($_POST['op']) ) {
	$op = $_POST['op'];
}

if (!file_exists(XOOPS_CACHE_PATH.'/adminmenu_'.$xoopsConfig['language'].'.php') && $op != 2) {
	xoops_header();
	xoops_confirm(array('op' => 2), 'admin.php', _RECREATE_ADMINMENU_FILE);
	xoops_footer();
	exit();
}

switch ($op){
	case 1:
		xoops_cp_header();
		showRSS(1);
		break;
	case 2:
		xoops_module_write_admin_menu(impresscms_get_adminmenu());
		redirect_header('admin.php', 1, _AD_LOGINADMIN);
		exit();
		break;
	
	/*
	 * Hack 
	 */
	case 10:
		$rssurl = 'http://www.impresscms.org/modules/smartsection/backend.php?categoryid=1';
		$rssfile = XOOPS_CACHE_PATH.'/www_smartsection_category1.xml';
		$caching_time = 1;
		$items_to_display = 1;
		
		$rssdata = '';
		if (!file_exists($rssfile) || filemtime($rssfile) < time() - $caching_time) {
			require_once XOOPS_ROOT_PATH.'/class/snoopy.php';
	        $snoopy = new Snoopy;
	        if ($snoopy->fetch($rssurl)) {
	            $rssdata = $snoopy->results;
	            if (false !== $fp = fopen($rssfile, 'w')) {
	                fwrite($fp, $rssdata);
	            }
	            fclose($fp);
	        }
		} else {
			if (false !== $fp = fopen($rssfile, 'r')) {
				while (!feof ($fp)) {
					$rssdata .= fgets($fp, 4096);
				}
				fclose($fp);
			}
		}
		if ($rssdata != '') {
			include_once XOOPS_ROOT_PATH.'/class/xml/rss/xmlrss2parser.php';
			$rss2parser = new XoopsXmlRss2Parser($rssdata);
			if (false != $rss2parser->parse()) {
				$items =& $rss2parser->getItems();
				$count = count($items);
				$myts =& MyTextSanitizer::getInstance();
				for ($i = 0; $i < $items_to_display; $i++) {
					?>					
						<div>
							<img style="vertical-align: middle;" src="<?=XOOPS_URL?>/modules/smartsection/images/icon/doc.png" alt="<?=$items[$i]['title']?>">&nbsp;<a href="<?=$items[$i]['guid']?>"><?=$items[$i]['title']?></a>
						</div>
						<div>
							<img class="smartsection_item_image" src="<?=XOOPS_URL?>/uploads/smartsection/images/item/impresscms_news.gif" alt="<?=$items[$i]['title']?>" title="<?=$items[$i]['title']?>" align="right">
							<?=$items[$i]['description']?>
					    </div>
						<div style="clear: both;"> </div>
						<div style="font-size: 10px; text-align: right;"><a href="http://www.impresscms.org/modules/smartsection/category.php?categoryid=1">All ImpressCMS Project news...</a></div>
					<?		
				}
			} else {
				//echo $rss2parser->getErrors();
			}
		}	
	break;
	
	default:
		$mods = xoops_cp_header(1);

		// ###### Output warn messages for security  ######
		if (is_dir(XOOPS_ROOT_PATH."/install/" )) {
			xoops_error(sprintf(_WARNINSTALL2,XOOPS_ROOT_PATH.'/install/'));
			echo '<br />';
		}
		if ( is_writable(XOOPS_ROOT_PATH."/mainfile.php" ) ) {
			xoops_error(sprintf(_WARNINWRITEABLE,XOOPS_ROOT_PATH.'/mainfile.php'));
			echo '<br />';
		}
		if (is_dir(XOOPS_ROOT_PATH."/upgrade/" )) {
			xoops_error(sprintf(_WARNINSTALL2,XOOPS_ROOT_PATH.'/upgrade/'));
			echo '<br />';
		}
		// ###### Output warn messages for correct functionality  ######
		if ( !is_writable( XOOPS_CACHE_PATH ) ) {
			xoops_warning(sprintf(_WARNINNOTWRITEABLE,XOOPS_CACHE_PATH));
			echo '<br />';
		}
		if ( !is_writable( XOOPS_UPLOAD_PATH ) ) {
			xoops_warning(sprintf(_WARNINNOTWRITEABLE,XOOPS_UPLOAD_PATH));
			echo '<br />';
		}
		if ( !is_writable( XOOPS_COMPILE_PATH ) ) {
			xoops_warning(sprintf(_WARNINNOTWRITEABLE,XOOPS_COMPILE_PATH));
			echo '<br />';
		}

		$icmsAdminTpl->assign('lang_cp', _CPHOME);
		$icmsAdminTpl->assign('lang_insmodules', _AD_INSTALLEDMODULES);

		// Loading allowed Modules
		$icmsAdminTpl->assign('modules', $mods);
		if (count($mods) > 0){
			$icmsAdminTpl->assign('modulesadm', 1);
		}else{
			$icmsAdminTpl->assign('modulesadm', 0);
		}

		// Loading System Configuration Links
		$groups = $xoopsUser->getGroups();
		$all_ok = false;
		if (!in_array(XOOPS_GROUP_ADMIN, $groups)) {
			$sysperm_handler =& xoops_gethandler('groupperm');
			$ok_syscats =& $sysperm_handler->getItemIds('system_admin', $groups);
		} else {
			$all_ok = true;
		}

		require_once XOOPS_ROOT_PATH."/class/xoopslists.php";
		require_once XOOPS_ROOT_PATH."/modules/system/constants.php";

		$admin_dir = XOOPS_ROOT_PATH."/modules/system/admin";
		$dirlist = XoopsLists::getDirListAsArray($admin_dir);

		if (file_exists(XOOPS_ROOT_PATH."/modules/system/language/".$xoopsConfig['language']."/admin.php")) {
			include XOOPS_ROOT_PATH."/modules/system/language/".$xoopsConfig['language']."/admin.php";
		} elseif (file_exists(XOOPS_ROOT_PATH."/modules/system/language/english/admin.php")) {
			include XOOPS_ROOT_PATH."/modules/system/language/english/admin.php";
		}

		$cont = 0;
		asort($dirlist);
		foreach($dirlist as $file){
			include $admin_dir.'/'.$file.'/xoops_version.php';

			if ($modversion['hasAdmin']) {
				$category = isset($modversion['category']) ? intval($modversion['category']) : 0;
				if (false != $all_ok || in_array($modversion['category'], $ok_syscats)) {
					$sysmod = array( "title" => $modversion['name'] , "link" => XOOPS_URL."/modules/system/admin.php?fct=".$file , "image" => XOOPS_URL."/modules/system/admin/$file/images/".$file."_big.png");
					$icmsAdminTpl->append( 'sysmod' , $sysmod  );
					$cont++;
				}
			}
			unset($modversion);
		}
		if ($cont > 0){
			$icmsAdminTpl->assign('systemadm', 1);
		}else{
			$icmsAdminTpl->assign('systemadm', 0);
		}

    	echo $icmsAdminTpl->fetch(XOOPS_ROOT_PATH.'/modules/system/templates/admin/system_indexcp.html');

		break;
}

function showRSS($op=1){
	switch ($op) {
		case 1:
			$rssurl = 'http://www.impresscms.org/modules/smartsection/backend.php';
			$rssfile = XOOPS_CACHE_PATH.'/adminnews.xml';
			break;
	}
	$rssdata = '';
	if (!file_exists($rssfile) || filemtime($rssfile) < time() - 86400) {
		require_once XOOPS_ROOT_PATH.'/class/snoopy.php';
        $snoopy = new Snoopy;
        if ($snoopy->fetch($rssurl)) {
            $rssdata = $snoopy->results;
            if (false !== $fp = fopen($rssfile, 'w')) {
                fwrite($fp, $rssdata);
            }
            fclose($fp);
        }
	} else {
		if (false !== $fp = fopen($rssfile, 'r')) {
			while (!feof ($fp)) {
				$rssdata .= fgets($fp, 4096);
			}
			fclose($fp);
		}
	}
	if ($rssdata != '') {
		include_once XOOPS_ROOT_PATH.'/class/xml/rss/xmlrss2parser.php';
		$rss2parser = new XoopsXmlRss2Parser($rssdata);
		if (false != $rss2parser->parse()) {
			echo '<table class="outer" width="100%">';
			$items =& $rss2parser->getItems();
			$count = count($items);
			$myts =& MyTextSanitizer::getInstance();
			for ($i = 0; $i < $count; $i++) {
				echo '<tr class="head"><td><a href="'.htmlspecialchars($items[$i]['link']).'" rel="external">';
				echo htmlspecialchars($items[$i]['title']).'</a> ('.htmlspecialchars($items[$i]['pubdate']).')</td></tr>';
				if ($items[$i]['description'] != "") {
					echo '<tr><td class="odd">'.utf8_decode($items[$i]['description']);
					if ($items[$i]['guid'] != "") {
						echo '&nbsp;&nbsp;<a href="'.htmlspecialchars($items[$i]['guid']).'" rel="external">'._MORE.'</a>';
					}
					echo '</td></tr>';
				} elseif ($items[$i]['guid'] != "") {
					echo '<tr><td class="even" valign="top"></td><td colspan="2" class="odd"><a href="'.htmlspecialchars($items[$i]['guid']).'" rel="external">'._MORE.'</a></td></tr>';
				}
			}
			echo '</table>';
		} else {
			echo $rss2parser->getErrors();
		}
	}
}

xoops_cp_footer();
?>