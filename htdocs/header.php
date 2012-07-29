<?php
/**
 * @copyright	The ImpressCMS Project <http://www.impresscms.org/>
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		core
 * @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		svn: $Id$
 */
defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

icms::$logger->stopTime('Module init');
icms::$logger->startTime('ICMS output init');

global $xoopsOption, $icmsConfig, $icmsModule;
$xoopsOption['theme_use_smarty'] = 1;

if (@$xoopsOption['template_main']) {
	if (FALSE === strpos($xoopsOption['template_main'], ':')) {
		$xoopsOption['template_main'] = 'db:' . $xoopsOption['template_main'];
	}
}
$xoopsThemeFactory = new icms_view_theme_Factory();
$xoopsThemeFactory->allowedThemes = $icmsConfig['theme_set_allowed'];
$xoopsThemeFactory->defaultTheme = $icmsConfig['theme_set'];

/**
 * @var icms_view_theme_Object
 */
$icmsTheme = $xoTheme =& $xoopsThemeFactory->createInstance(array('contentTemplate' => @$xoopsOption['template_main'],));
$xoopsTpl = $icmsTpl =& $xoTheme->template;

include_once(ICMS_LIBRARIES_PATH . '/jscore/icmsObject.php');

if ($icmsConfigMetaFooter['use_google_analytics'] === TRUE
	&& isset($icmsConfigMetaFooter['google_analytics']) && $icmsConfigMetaFooter['google_analytics'] != '') {
	/* Legacy GA urchin code */
	//$xoTheme->addScript('http://www.google-analytics.com/urchin.js',array('type' => 'text/javascript'),'_uacct = "UA-' . $icmsConfigMetaFooter['google_analytics'] . '";urchinTracker();');
	$scheme = parse_url(ICMS_URL, PHP_URL_SCHEME);
	if ($scheme == 'http') {
		/* New GA code, http protocol */
		$xoTheme->addScript('http://www.google-analytics.com/ga.js', array('type' => 'text/javascript'),'');
	} elseif ($scheme == 'https') {
		/* New GA code, https protocol */
		$xoTheme->addScript('https://ssl.google-analytics.com/ga.js', array('type' => 'text/javascript'),'');
	}
}
if (isset($icmsConfigMetaFooter['google_meta']) && $icmsConfigMetaFooter['google_meta'] != '') {
	$xoTheme->addMeta('meta', 'verify-v1', $icmsConfigMetaFooter['google_meta']);
	$xoTheme->addMeta('meta', 'google-site-verification', $icmsConfigMetaFooter['google_meta']);
}
// ################# Preload Trigger startOutputInit ##############
icms::$preload->triggerEvent('startOutputInit');

if (@is_object($xoTheme->plugins['icms_view_PageBuilder'])) {
	$aggreg =& $xoTheme->plugins['icms_view_PageBuilder'];
	$xoopsTpl->assign_by_ref('xoBlocks', $aggreg->blocks);

	// Backward compatibility code for pre 2.0.14 themes
	$xoopsTpl->assign_by_ref('xoops_lblocks', $aggreg->blocks['canvas_left']);
	$xoopsTpl->assign_by_ref('xoops_rblocks', $aggreg->blocks['canvas_right']);
	$xoopsTpl->assign_by_ref('xoops_ccblocks', $aggreg->blocks['page_topcenter']);
	$xoopsTpl->assign_by_ref('xoops_clblocks', $aggreg->blocks['page_topleft']);
	$xoopsTpl->assign_by_ref('xoops_crblocks', $aggreg->blocks['page_topright']);

	$xoopsTpl->assign('xoops_showlblock', !empty($aggreg->blocks['canvas_left']));
	$xoopsTpl->assign('xoops_showrblock', !empty($aggreg->blocks['canvas_right']));
	$xoopsTpl->assign('xoops_showcblock', !empty($aggreg->blocks['page_topcenter'])
												|| !empty($aggreg->blocks['page_topleft'])
												|| !empty($aggreg->blocks['page_topright'])
											);
}

if ($icmsModule )
$xoTheme->contentCacheLifetime = @$icmsConfig['module_cache'][$icmsModule->getVar('mid', 'n')];

// Assigning the selected language as a smarty var
$xoopsTpl->assign('icmsLang', $icmsConfig['language']);

icms::$logger->stopTime('ICMS output init');
icms::$logger->startTime('Module display');
