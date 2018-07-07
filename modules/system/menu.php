<?php
/**
 * Contains links to admin options and images for those admin options
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		Administration
 * @since		1.3
 */

// Loading System Configuration Links
if (is_object(icms::$user)) {
	$groups = icms::$user->getGroups();
} else {
	$groups = array();
}
$all_ok = false;
if (!in_array(ICMS_GROUP_ADMIN, $groups)) {
	$sysperm_handler = icms::handler('icms_member_groupperm');
	$ok_syscats = & $sysperm_handler->getItemIds('system_admin', $groups);
} else {
	$all_ok = true;
}

require_once ICMS_ROOT_PATH . '/modules/system/constants.php';

$admin_dir = ICMS_ROOT_PATH . '/modules/system/admin';
$dirlist = icms_core_Filesystem::getDirList($admin_dir);

icms_loadLanguageFile('system', 'admin');
$adminmenu = array();
foreach ($dirlist as $file) {
	icms_loadLanguageFile('system', $file, true);
	$mod_version_file = 'icms_version.php';
	include $admin_dir . '/' . $file . '/' . $mod_version_file;
	if ($modversion['hasAdmin']) {
		$category = isset($modversion['category'])?(int) ($modversion['category']):0;
		if (false != $all_ok || in_array($modversion['category'], $ok_syscats)) {
			$adminmenu[$modversion['group']]['title'] = $modversion['group'];
			$adminmenu[$modversion['group']]['link'] = "#";
			$adminmenu[$modversion['group']]['absolute']	= 1;
			$adminmenu[$modversion['group']]['hassubs']		= 1;
			if ($modversion['name'] == _MD_AM_PREF) {
				//Getting categories of preferences to include in dropdownmenu
				icms_loadLanguageFile('system', 'preferences', true);
				$confcat_handler = icms::handler('icms_config_category');
				$confcats = $confcat_handler->getObjects();
				$catcount = count($confcats);
				if ($catcount > 0) {
					for ($x = 0; $x < $catcount; $x++) {
						$subs[$x]['title'] = constant($confcats[$x]->getVar('confcat_name'));
						$subs[$x]['link'] = ICMS_URL . '/modules/system/admin.php?fct=preferences'
							. '&amp;op=show&amp;confcat_id=' . $confcats[$x]->getVar('confcat_id');
					}
					$adminmenu[$modversion['group']]['subs'][] = array(
						'title'		=> $modversion['name'],
						'link'		=> ICMS_URL . '/modules/system/admin.php?fct=' . $file,
						'icon'		=> 'admin/' . $file . '/images/' . $file . '.png',
						'small'		=> 'admin/' . $file . '/images/' . $file . '_small.png',
						'id'		=> $modversion['category'],
						'hassubs'	=> 1,
						'subs'		=> $subs
					);
				}
			} else {
				$adminmenu[$modversion['group']]['subs'][] = array(
					'title'	=> $modversion['name'],
					'link'	=> ICMS_URL . '/modules/system/admin.php?fct=' . $file,
					'icon'	=> 'admin/' . $file . '/images/' . $file . '.png',
					'small'	=> 'admin/' . $file . '/images/' . $file . '_small.png',
					'id'	=> $modversion['category']
				);
			}
		}
	}
	unset($modversion);
}
if (count($adminmenu) > 0) {
	ksort($adminmenu);
	return $adminmenu;
}
