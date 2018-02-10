<?php
/**
 * Temporary solution for "site closed" status
 *
 * @copyright	The Xoops project http://www.xoops.org/
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		phppp (infomax@gmail.com)
 * @since		Xoops 2.0.17
 * @package 	core
 * @version		SVN: $Id$
 */

$allowed = FALSE;
if (isset($xoopsOption['ignore_closed_site']) && $xoopsOption['ignore_closed_site']) {
	$allowed = TRUE;
} elseif (is_object(icms::$user)) {
	foreach (icms::$user->getGroups() as $group) {
		if (in_array($group, $icmsConfig['closesite_okgrp']) || ICMS_GROUP_ADMIN == $group) {
			$allowed = TRUE;
			break;
		}
	}
} elseif (!empty($_POST['xoops_login'])) {
	include_once ICMS_INCLUDE_PATH . '/checklogin.php';
	exit();
}

if (!$allowed) {
	$themeFactory = new icms_view_theme_Factory();
	$themeFactory->allowedThemes = $icmsConfig['theme_set_allowed'];
	$themeFactory->defaultTheme = $icmsConfig['theme_set'];
	$icmsTheme =& $themeFactory->createInstance(array("plugins" => array()));
	$icmsTheme->addScript('/include/xoops.js', array('type' => 'text/javascript'));
	/** @todo	Remove icms.css in 2.0 */
	icms_core_Debug::setDeprecated("Elements from icms.css need to be moved to your theme", sprintf(_CORE_REMOVE_IN_VERSION, '2.0'));
	$icmsTheme->addStylesheet(ICMS_URL . "/icms"
		. ((defined('_ADM_USE_RTL') && _ADM_USE_RTL) ? "_rtl" : "") . ".css", array("media" => "screen"));
	$icmsTpl =& $icmsTheme->template;

	$icmsTpl->assign(array(
		'icms_theme' => $icmsConfig['theme_set'],
		'icms_imageurl' => ICMS_THEME_URL . '/' . $icmsConfig['theme_set'] . '/',
		'icms_themecss' => xoops_getcss($icmsConfig['theme_set']),
		'icms_requesturi' => htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, _CHARSET),
		'icms_sitename' => htmlspecialchars($icmsConfig['sitename'], ENT_QUOTES, _CHARSET),
		'icms_slogan' => htmlspecialchars($icmsConfig['slogan'], ENT_QUOTES, _CHARSET),
		'icms_dirname' => @$icmsModule ? $icmsModule->getVar('dirname') : 'system',
		'icms_pagetitle' => isset($icmsModule) && is_object($icmsModule)
			? $icmsModule->getVar('name')
			: htmlspecialchars($icmsConfig['slogan'], ENT_QUOTES, _CHARSET),
		'lang_login' => _LOGIN,
		'lang_username' => _USERNAME,
		'lang_password' => _PASSWORD,
		'lang_siteclosemsg' => $icmsConfig['closesite_text'])
	);

	foreach ($icmsConfigMetaFooter as $name => $value) {
		if (substr($name, 0, 5) == 'meta_') {
			$icmsTpl->assign("xoops_$name", htmlspecialchars($value, ENT_QUOTES, _CHARSET));
		} else {
			$icmsTpl->assign("xoops_$name", $value);
		}
	}
	$icmsTpl->debugging = FALSE;
	$icmsTpl->debugging_ctrl = 'NONE';
	$icmsTpl->caching = 0;

	icms_loadLanguageFile("system", "customtag", TRUE);
	icms_Autoloader::register(ICMS_MODULES_PATH . "/system/class", "mod_system");
	$icms_customtag_handler = icms_getModuleHandler("customtag", "system");
	$customtags_array = array();
	if (is_object($icmsTpl)) {
		foreach ($icms_customtag_handler->getCustomtagsByName() as $k => $v) {
			$customtags_array[$k] = $v->render();
		}
		$icmsTpl->assign('icmsCustomtags', $customtags_array);
	}

	$icmsTpl->display('db:system_siteclosed.html');
	exit();
}
unset($allowed, $group);

return TRUE;