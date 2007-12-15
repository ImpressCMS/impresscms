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

function showRSS($op=1){
	switch ($op) {
		case 1:
			$rssurl = 'http://impresscms.org/modules/smartsection/backend.php';
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
				echo '<tr class="head"><td><a href="'.htmlspecialchars($items[$i]['link']).'" target="_blank">';
				echo htmlspecialchars($items[$i]['title']).'</a> ('.htmlspecialchars($items[$i]['pubdate']).')</td></tr>';
				if ($items[$i]['description'] != "") {
					echo '<tr><td class="odd">'.utf8_decode($items[$i]['description']);
					if ($items[$i]['guid'] != "") {
						echo '&nbsp;&nbsp;<a href="'.htmlspecialchars($items[$i]['guid']).'" target="_blank">'._MORE.'</a>';
					}
					echo '</td></tr>';
				} elseif ($items[$i]['guid'] != "") {
					echo '<tr><td class="even" valign="top"></td><td colspan="2" class="odd"><a href="'.htmlspecialchars($items[$i]['guid']).'" target="_blank">'._MORE.'</a></td></tr>';
				}
			}
			echo '</table>';
		} else {
			echo $rss2parser->getErrors();
		}
	}
}

/**
 * XOOPS News
 */
$op = isset($_GET['rssnews']) ? $_GET['rssnews'] : 0;

switch ($op){
	case 1:
		xoops_cp_header();
		showRSS(1);
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

		$tpl->assign('lang_cp', _CPHOME);
		$tpl->assign('lang_insmodules', _AD_INSTALLEDMODULES);

		/*$tpl->assign('lang_banners', _AD_BANNERS);
		$tpl->assign('lang_blocks', _AD_BLOCKS);
		$tpl->assign('lang_groups', _AD_GROUPS);
		$tpl->assign('lang_images', _AD_IMAGES);
		$tpl->assign('lang_modules', _MODULES);
		$tpl->assign('lang_preferences', _AD_PREFERENCES);
		$tpl->assign('lang_smilies', _AD_SMILIES);
		$tpl->assign('lang_ranks', _AD_RANK);
		$tpl->assign('lang_edituser', _AD_EDITUSER);
		$tpl->assign('lang_finduser', _AD_FINDUSER);
		$tpl->assign('lang_mailuser', _AD_MAILUSER);
		$tpl->assign('lang_avatars', _AD_AVATARS);
		$tpl->assign('lang_tpls', _AD_TPLSETS);
		$tpl->assign('lang_comments', _AD_COMMENTS);
*/
		// Loading all the installed Modules

		foreach ($mods as $mod){
			$rtn = array();
			$moduleperm_handler =& xoops_gethandler('groupperm');
			$sadmin = $moduleperm_handler->checkRight('module_admin', $mod->mid(), $xoopsUser->getGroups());
			if ($sadmin){
				$info =& $mod->getInfo();
				$rtn['link'] = XOOPS_URL . '/modules/'. $mod->dirname() . '/' . $info['adminindex'];
				$rtn['title'] = $mod->name();
				$rtn['absolute'] = 1;
				if (isset($info['iconbig'])) $rtn['big'] = XOOPS_URL . '/modules/' . $mod->dirname() . '/' . $info['iconbig'];
			}
			$tpl->append('modules', $rtn);
		}

		// Loading of System Configuration Links
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

		foreach($dirlist as $file){
			include $admin_dir.'/'.$file.'/xoops_version.php';

			if ($modversion['hasAdmin']) {
				$category = isset($modversion['category']) ? intval($modversion['category']) : 0;
				if (false != $all_ok || in_array($modversion['category'], $ok_syscats)) {
					$sysmod = array( "title" => $modversion['name'] , "link" => XOOPS_URL."/modules/system/admin.php?fct=".$file , "image" => XOOPS_URL."/modules/system/admin/$file/images/".$file."_big.png");
					$tpl->append( 'sysmod' , $sysmod  );
				}
			}
			unset($modversion);
		}


		/**
		 * Recommended Links
		 * TODO: Not really needed to be in the admin. It is goin to be deleted if all core team agree.
		 */
		/*// Commented... was generating errors.
		//$rssurl = "";
		//$rssfile = XOOPS_CACHE_PATH.'/blabla_recommended_links.xml';

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
					$tpl->append('rss_links', array('link'=>htmlspecialchars($items[$i]['link']),'title'=>htmlspecialchars($items[$i]['title']),
							'desc'=>utf8_decode($items[$i]['description'])));
				}
			} else {
				echo $rss2parser->getErrors();
			}
		}
		*/
		echo $tpl->fetch(XOOPS_ROOT_PATH.'/modules/system/templates/admin/system_indexcp.html');

		break;
}

xoops_cp_footer();
?>