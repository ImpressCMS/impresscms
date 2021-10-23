<?php

// Skip for ORETEKI XOOPS
if (defined('XOOPS_ORETEKI')) return;

global $xoopsModule;
if (!is_object($xoopsModule)) die('$xoopsModule is not set');

// for RTL users
@define('_GLOBAL_LEFT', @_ADM_USE_RTL == 1 ? 'right' : 'left');
@define('_GLOBAL_RIGHT', @_ADM_USE_RTL == 1 ? 'left' : 'right');

// language files (modinfo.php)
$language = empty($icmsConfig['language']) ? 'english' : $icmsConfig['language'];
if (file_exists("$mydirpath/language/$language/modinfo.php")) {
	// user customized language file
	include_once "$mydirpath/language/$language/modinfo.php";
} else if (file_exists("$mytrustdirpath/language/$language/modinfo.php")) {
	// default language file
	include_once "$mytrustdirpath/language/$language/modinfo.php";
} else {
	// fallback english
	include_once "$mytrustdirpath/language/english/modinfo.php";
}

include dirname(__DIR__) . '/admin_menu.php';

// preferences
$config_handler = icms::handler('icms_config');
if (count($config_handler->getConfigs(new icms_db_criteria_Item('conf_modid', $xoopsModule->getVar('mid')))) > 0) {

	// system->preferences
	array_push($adminmenu, array (
		'title' => _PREFERENCES,
		'link' => ICMS_URL . '/modules/system/admin.php?fct=preferences&op=showmod&mod=' . $xoopsModule->getVar('mid')
	));
}

$mymenu_uri = empty($mymenu_fake_uri) ? $_SERVER['REQUEST_URI'] : $mymenu_fake_uri;
$mymenu_link = substr(strstr($mymenu_uri, '/admin/'), 1);

// highlight (you can customize the colors)
foreach (array_keys($adminmenu) as $i) {
	if ($mymenu_link == $adminmenu[$i]['link']) {
		$adminmenu[$i]['color'] = '#FFCCCC';
		$adminmenu_hilighted = true;
		$GLOBALS['altsysAdminPageTitle'] = $adminmenu[$i]['title'];
	} else {
		$adminmenu[$i]['color'] = '#DDDDDD';
	}
}
if (empty($adminmenu_hilighted)) {
	foreach (array_keys($adminmenu) as $i) {
		if (stristr($mymenu_uri, $adminmenu[$i]['link'])) {
			$adminmenu[$i]['color'] = '#FFCCCC';
			$GLOBALS['altsysAdminPageTitle'] = $adminmenu[$i]['title'];
			break;
		}
	}
}

// link conversion from relative to absolute
foreach (array_keys($adminmenu) as $i) {
	if (stristr($adminmenu[$i]['link'], ICMS_URL) === false) {
		$adminmenu[$i]['link'] = ICMS_URL . "/modules/$mydirname/" . $adminmenu[$i]['link'];
	}
}

// display (you can customize htmls)
echo "<div style='text-align:" . _GLOBAL_LEFT . ";width:98%;'>";
foreach ($adminmenu as $menuitem) {
	echo "<div style='float:" . _GLOBAL_LEFT . ";height:1.5em;'><nobr><a href='" . htmlspecialchars($menuitem['link'], ENT_QUOTES) . "' style='background-color:{$menuitem['color']};font:normal normal bold 9pt/12pt;'>" . htmlspecialchars($menuitem['title'], ENT_QUOTES) . "</a> | </nobr></div>\n";
}
echo "</div>\n<hr style='clear:" . _GLOBAL_LEFT . ";display:block;' />\n";
