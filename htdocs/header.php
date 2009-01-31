<?php
/**
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		core
* @since		XOOPS
* @author		http://www.xoops.org The XOOPS Project
* @author	   Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
* @version		$Id$
*/
defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');
/** Including the xoopsblock class to handle block display */
include_once ICMS_ROOT_PATH.'/class/xoopsblock.php';

//global $xoopsLogger;
if(!isset($xoopsLogger)) {$xoopsLogger =& $GLOBALS['xoopsLogger'];}
if(!isset($icmsPreloadHandler)) {$icmsPreloadHandler =& $GLOBALS['icmsPreloadHandler'];}
$xoopsLogger->stopTime('Module init');
$xoopsLogger->startTime('ICMS output init');

if($xoopsConfig['theme_set'] != 'default' && file_exists(ICMS_THEME_PATH.'/'.$xoopsConfig['theme_set'].'/theme.php'))
{
	/** For backwards compatibility with XOOPS 1.3.x */
  require_once ICMS_ROOT_PATH.'/include/xoops13_header.php';
}
else
{
	global $xoopsOption, $xoopsConfig, $xoopsModule;
    	$xoopsOption['theme_use_smarty'] = 1;

    	/**  include Smarty template engine and initialize it*/
    	require_once ICMS_ROOT_PATH.'/class/template.php';
    	require_once ICMS_ROOT_PATH.'/class/theme.php';
    	require_once ICMS_ROOT_PATH.'/class/theme_blocks.php';

	if(@$xoopsOption['template_main'])
	{
		if(false === strpos($xoopsOption['template_main'], ':')) {$xoopsOption['template_main'] = 'db:' . $xoopsOption['template_main'];}
	}
	$xoopsThemeFactory =& new xos_opal_ThemeFactory();
    	$xoopsThemeFactory->allowedThemes = $xoopsConfig['theme_set_allowed'];
    	$xoopsThemeFactory->defaultTheme = $xoopsConfig['theme_set'];

	/**
	* @var xos_opal_Theme
	*/
    	$xoTheme =& $xoopsThemeFactory->createInstance(array('contentTemplate' => @$xoopsOption['template_main'],));
    	$xoopsTpl =& $xoTheme->template;
	$config_handler =& xoops_gethandler('config');
	$xoopsConfigMetaFooter =& $config_handler->getConfigsByCat(XOOPS_CONF_METAFOOTER);
	if (isset($xoopsConfigMetaFooter['google_meta']) && $xoopsConfigMetaFooter['google_meta'] != ''){
		$arr_google_meta=explode('"', '<meta name="verify-v1" content="'.$xoopsConfigMetaFooter['google_meta'].'" />');
		$xoTheme->addMeta('meta',$arr_google_meta[1],$arr_google_meta[3]);
	}
	// ################# Preload Trigger startOutputInit ##############
	$icmsPreloadHandler->triggerEvent('startOutputInit');
	
	$xoTheme->addScript(ICMS_URL.'/include/xoops.js', array('type' => 'text/javascript'));
	$xoTheme->addScript(ICMS_URL.'/include/linkexternal.js', array('type' => 'text/javascript'));
	/** 
	 * Now system first checks for RTL, if it is enabled it'll just load it, otherwise it will load the normal (LTR) styles
	*/
    $xoTheme->addStylesheet(ICMS_URL."/icms".(( defined('_ADM_USE_RTL') && _ADM_USE_RTL )?"_rtl":"").".css", array("media" => "screen"));
    // Weird, but need extra <script> tags for 2.0.x themes
    //$xoopsTpl->assign('xoops_js', '//--></script><script type="text/javascript" src="'.ICMS_URL.'/include/xoops.js"></script><script type="text/javascript"><!--');
	//$xoopsTpl->assign('linkexternal_js', '//--></script><script type="text/javascript" src="'.ICMS_URL.'/include/linkexternal.js"></script><script type="text/javascript"><!--');
    // Weird, but we need to bring plugins java information in header because doing it form elsewhere will drop system required Java Script files!!
			$config_handler =& xoops_gethandler('config');
			$icmsConfigPlugins =& $config_handler->getConfigsByCat(ICMS_CONF_PLUGINS);
 			$jscript = '';
			if(class_exists('XoopsFormDhtmlTextArea')){
 		        foreach ($icmsConfigPlugins['sanitizer_plugins'] as $key) {
 		        	$extension = include_once ICMS_ROOT_PATH.'/plugins/textsanitizer/'.$key.'/'.$key.'.php';
 		        	$func = 'javascript_'.$key;
 		        	if ( function_exists($func) ) {
 		        		@list($encode, $jscript) = $func($ele_name);
 		        		 	if(file_exists(ICMS_ROOT_PATH.'/plugins/textsanitizer/'.$key.'/'.$key.'.js')){
 		        				$xoTheme->addScript(ICMS_URL.'/plugins/textsanitizer/'.$key.'/'.$key.'.js', array('type' => 'text/javascript'));
 		        			}elseif (!empty($jscript)) {
 		        				if(!file_exists(ICMS_ROOT_PATH.'/'.$jscript)){
 		        					$xoTheme->addScript('', array('type' => 'text/javascript'), $jscript);
 		        				}else{
 		        					$xoTheme->addScript(ICMS_URL.'/'.$jscript, array('type' => 'text/javascript'));
 		        			}
 		        		}
 		        	}
 		        }
 		    }

	if(@is_object($xoTheme->plugins['xos_logos_PageBuilder']))
	{
		$aggreg =& $xoTheme->plugins['xos_logos_PageBuilder'];
	    	$xoopsTpl->assign_by_ref('xoBlocks', $aggreg->blocks);

	    	// Backward compatibility code for pre 2.0.14 themes
		$xoopsTpl->assign_by_ref('xoops_lblocks', $aggreg->blocks['canvas_left']);
		$xoopsTpl->assign_by_ref('xoops_rblocks', $aggreg->blocks['canvas_right']);
		$xoopsTpl->assign_by_ref('xoops_ccblocks', $aggreg->blocks['page_topcenter']);
		$xoopsTpl->assign_by_ref('xoops_clblocks', $aggreg->blocks['page_topleft']);
		$xoopsTpl->assign_by_ref('xoops_crblocks', $aggreg->blocks['page_topright']);

		$xoopsTpl->assign('xoops_showlblock', !empty($aggreg->blocks['canvas_left']));
		$xoopsTpl->assign('xoops_showrblock', !empty($aggreg->blocks['canvas_right']));
		$xoopsTpl->assign('xoops_showcblock', !empty($aggreg->blocks['page_topcenter']) || !empty($aggreg->blocks['page_topleft']) || !empty($aggreg->blocks['page_topright']));
	}
	if($xoopsModule) {$xoTheme->contentCacheLifetime = @$xoopsConfig['module_cache'][$xoopsModule->getVar('mid', 'n')];}
	if($xoTheme->checkCache()) {exit();}

    	if(!isset($xoopsOption['template_main']) && $xoopsModule)
	{
        	// new themes using Smarty does not have old functions that are required in old modules, so include them now
        	include ICMS_ROOT_PATH.'/include/old_theme_functions.php';
        	// need this also
        	$xoopsTheme['thename'] = $xoopsConfig['theme_set'];
        	ob_start();
    	}

	// assigning the selected language as a smarty var
	$xoopsTpl->assign('icmsLang', $xoopsConfig['language']);

	$xoopsLogger->stopTime('ICMS output init');
	$xoopsLogger->startTime('Module display');
}
?>
