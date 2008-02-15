<?php
// $Id: header.php 1029 2007-09-09 03:49:25Z phppp $
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
defined("XOOPS_ROOT_PATH") or die( 'XOOPS root path not defined' );

include_once XOOPS_ROOT_PATH.'/class/xoopsblock.php';

//global $xoopsLogger;

if ( !isset( $xoopsLogger ) ) {		$xoopsLogger =& $GLOBALS['xoopsLogger'];	}

$xoopsLogger->stopTime( 'Module init' );
$xoopsLogger->startTime( 'XOOPS output init' );


if ($xoopsConfig['theme_set'] != 'default' && file_exists(XOOPS_THEME_PATH.'/'.$xoopsConfig['theme_set'].'/theme.php')) {
	require_once XOOPS_ROOT_PATH . '/include/xoops13_header.php';
} else {
	global $xoopsOption, $xoopsConfig, $xoopsModule;

    $xoopsOption['theme_use_smarty'] = 1;

    // include Smarty template engine and initialize it
    require_once XOOPS_ROOT_PATH . '/class/template.php';
    require_once XOOPS_ROOT_PATH . '/class/theme.php';
    require_once XOOPS_ROOT_PATH . '/class/theme_blocks.php';

	if ( @$xoopsOption['template_main'] ) {
		if ( false === strpos( $xoopsOption['template_main'], ':' ) ) {
			$xoopsOption['template_main'] = 'db:' . $xoopsOption['template_main'];
		}
	}
	$xoopsThemeFactory =& new xos_opal_ThemeFactory();
    $xoopsThemeFactory->allowedThemes = $xoopsConfig['theme_set_allowed'];
    $xoopsThemeFactory->defaultTheme = $xoopsConfig['theme_set'];

	/**
	 * @var xos_opal_Theme
	 */
    $xoTheme =& $xoopsThemeFactory->createInstance( array(
    	'contentTemplate' => @$xoopsOption['template_main'],
    ) );
    $xoopsTpl =& $xoTheme->template;

    // triggering event "startOutputInit" of third party integration
	global $icmsLibrariesHandler;
	$icmsLibrariesHandler->triggerEvent('startOutputInit');
	$xoTheme->addScript( '/include/xoops.js', array( 'type' => 'text/javascript' ) );
	$xoTheme->addScript( '/include/linkexternal.js', array( 'type' => 'text/javascript' ) );
	
    // Weird, but need extra <script> tags for 2.0.x themes
    //$xoopsTpl->assign('xoops_js', '//--></script><script type="text/javascript" src="'.XOOPS_URL.'/include/xoops.js"></script><script type="text/javascript"><!--');
	//$xoopsTpl->assign('linkexternal_js', '//--></script><script type="text/javascript" src="'.XOOPS_URL.'/include/linkexternal.js"></script><script type="text/javascript"><!--');

	if ( @is_object( $xoTheme->plugins['xos_logos_PageBuilder'] ) ) {
		$aggreg =& $xoTheme->plugins['xos_logos_PageBuilder'];

	    $xoopsTpl->assign_by_ref( 'xoBlocks', $aggreg->blocks );

	    // Backward compatibility code for pre 2.0.14 themes
		$xoopsTpl->assign_by_ref( 'xoops_lblocks', $aggreg->blocks['canvas_left'] );
		$xoopsTpl->assign_by_ref( 'xoops_rblocks', $aggreg->blocks['canvas_right'] );
		$xoopsTpl->assign_by_ref( 'xoops_ccblocks', $aggreg->blocks['page_topcenter'] );
		$xoopsTpl->assign_by_ref( 'xoops_clblocks', $aggreg->blocks['page_topleft'] );
		$xoopsTpl->assign_by_ref( 'xoops_crblocks', $aggreg->blocks['page_topright'] );

		$xoopsTpl->assign( 'xoops_showlblock', !empty($aggreg->blocks['canvas_left']) );
		$xoopsTpl->assign( 'xoops_showrblock', !empty($aggreg->blocks['canvas_right']) );
		$xoopsTpl->assign( 'xoops_showcblock', !empty($aggreg->blocks['page_topcenter']) || !empty($aggreg->blocks['page_topleft']) || !empty($aggreg->blocks['page_topright']) );
	}

	if ( $xoopsModule ) {
		$xoTheme->contentCacheLifetime = @$xoopsConfig['module_cache'][ $xoopsModule->getVar('mid', 'n') ];
	}
	if ( $xoTheme->checkCache() ) {
		exit();
	}

    if ( !isset($xoopsOption['template_main']) && $xoopsModule ) {
        // new themes using Smarty does not have old functions that are required in old modules, so include them now
        include XOOPS_ROOT_PATH.'/include/old_theme_functions.php';
        // need this also
        $xoopsTheme['thename'] = $xoopsConfig['theme_set'];
        ob_start();
    }

$xoopsLogger->stopTime( 'XOOPS output init' );
$xoopsLogger->startTime( 'Module display' );


}

?>