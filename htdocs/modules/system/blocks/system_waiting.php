<?php
// $Id: waiting_waiting.php,v 1.8 2005/04/20 03:43:55 gij Exp $
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
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

// EXTENSIBLE "waiting block" by plugins in both waiting and modules

function b_system_waiting_show($options)
{
    global $xoopsUser, $xoopsConfig;

    $userlang = $xoopsConfig['language'] ;

	$sql_cache_min = empty( $options[1] ) ? 0 : intval( $options[1] ) ;
	$sql_cache_file = XOOPS_CACHE_PATH.'/waiting_touch' ;

	// SQL cache check (you have to use this cache with block's cache by system)
	if( file_exists( $sql_cache_file ) ) {
		$sql_cache_mtime = filemtime( $sql_cache_file ) ;
		if( time() < $sql_cache_mtime + $sql_cache_min * 60 ) return array() ;
		else {
			unlink( $sql_cache_file ) ;
		}
	}


	// read language files for plugins
	icms_loadLanguageFile('system', 'plugins');

	$plugins_path = ICMS_PLUGINS_PATH."/waiting";
	$xoopsDB =& Database::getInstance();
	$module_handler =& xoops_gethandler('module');
	$block = array();

	// get module's list installed
	$mod_lists = $module_handler->getList(new Criteria(1,1),true);
	foreach( $mod_lists as $dirname => $name ) {

		$plugin_info = system_get_plugin_info( $dirname , $xoopsConfig['language'] ) ;
		if( empty( $plugin_info ) || empty( $plugin_info['plugin_path'] ) ) continue ;

		if( ! empty( $plugin_info['langfile_path'] ) ) {
			include_once $plugin_info['langfile_path'] ;
		}
		include_once $plugin_info['plugin_path'] ;

		// call the plugin
		if( function_exists( @$plugin_info['func'] ) ) {
			// get the list of waitings
			$_tmp = call_user_func( $plugin_info['func'] , $dirname ) ;
			if( isset( $_tmp["lang_linkname"] ) ) {
				if( @$_tmp["pendingnum"] > 0 || $options[0] > 0){
					$block["modules"][$dirname]["pending"][] = $_tmp;
				}
				unset( $_tmp ) ;
			} else {
				// Judging the plugin returns multiple items
				// if lang_linkname does not exist
				foreach( $_tmp as $_one ) {
					if( @$_one["pendingnum"] > 0 || $options[0] > 0){
						$block["modules"][$dirname]["pending"][] = $_one;
					}
				}
			}
		}

		// for older compatibilities
		// Hacked by GIJOE
		$i = 0 ;
		while( 1 ) {
			$function_name = "b_waiting_{$dirname}_$i" ;
			if (function_exists( $function_name )){
				$_tmp = call_user_func( $function_name ) ;
				++ $i ;
				if($_tmp["pendingnum"] > 0 || $options[0] > 0){
					$block["modules"][$dirname]["pending"][] = $_tmp;
				}
				unset($_tmp);
			} else break ;
		}
		// End of Hack

		// if(count($block["modules"][$dirname]) > 0){
		if ( ! empty( $block["modules"][$dirname] ) ) {
			$block["modules"][$dirname]["name"] = $name;
		}
	}
	//print_r($block);

	// SQL cache touch (you have to use this cache with block's cache by system)
	if( empty( $block ) && $sql_cache_min > 0 ) {
		$fp = fopen( $sql_cache_file , "w" ) ;
		fclose( $fp ) ;
	}

	return $block ;
}

function b_system_waiting_edit($options){

	$sql_cache_min = empty( $options[1] ) ? 0 : intval( $options[1] ) ;

	$form = _MB_SYSTEM_NOWAITING_DISPLAY.":&nbsp;<input type='radio' name='options[0]' value='1'";
	if ( $options[0] == 1 ) {
		$form .= " checked='checked'";
	}
	$form .= " />&nbsp;"._YES."<input type='radio' name='options[0]' value='0'";
	if ( $options[0] == 0 ) {
		$form .= " checked='checked'";
	}
	$form .=" />&nbsp;"._NO."<br />\n";
	$form .= sprintf( _MINUTES , _MB_SYSTEM_SQL_CACHE.":&nbsp;<input type='text' name='options[1]' value='$sql_cache_min' size='2' />" ) ;

	return $form;
}
function system_get_plugin_info( $dirname , $language = 'english' )
{
	// get $mytrustdirname for D3 modules
	$mytrustdirname = '' ;
	if( defined( 'XOOPS_TRUST_PATH' ) && file_exists( XOOPS_ROOT_PATH."/modules/".$dirname."/mytrustdirname.php" ) ) {
		@include XOOPS_ROOT_PATH."/modules/".$dirname."/mytrustdirname.php" ;
	}

	$module_plugin_file = XOOPS_ROOT_PATH."/modules/".$dirname."/include/waiting.plugin.php" ;
	$d3module_plugin_file = XOOPS_TRUST_PATH."/modules/".$mytrustdirname."/include/waiting.plugin.php" ;
	$builtin_plugin_file = ICMS_PLUGINS_PATH."/waiting/".$dirname.".php" ;

	if( file_exists( $module_plugin_file ) ) {
		// module side (1st priority)
		$lang_files = array(
			XOOPS_ROOT_PATH."/modules/$dirname/language/$language/waiting.php" ,
			XOOPS_ROOT_PATH."/modules/$dirname/language/english/waiting.php" ,
		) ;
		$langfile_path = '' ;
		foreach( $lang_files as $lang_file ) {
			if( file_exists( $lang_file ) ) {
				$langfile_path = $lang_file ;
				break ;
			}
		}
		$ret = array(
			'plugin_path' => $module_plugin_file ,
			'langfile_path' => $langfile_path ,
			'func' => 'b_waiting_'.$dirname ,
			'type' => 'module' ,
		) ;
	} else if( ! empty( $mytrustdirname ) && file_exists( $d3module_plugin_file ) ) {
		// D3 module's plugin under xoops_trust_path (2nd priority)
		$lang_files = array(
			XOOPS_TRUST_PATH."/modules/$mytrustdirname/language/$language/waiting.php" ,
			XOOPS_TRUST_PATH."/modules/$mytrustdirname/language/english/waiting.php" ,
		) ;
		$langfile_path = '' ;
		foreach( $lang_files as $lang_file ) {
			if( file_exists( $lang_file ) ) {
				$langfile_path = $lang_file ;
				break ;
			}
		}
		$ret = array(
			'plugin_path' => $d3module_plugin_file ,
			'langfile_path' => $langfile_path ,
			'func' => 'b_waiting_'.$mytrustdirname ,
			'type' => 'module (D3)' ,
		) ;
	} else if( file_exists( $builtin_plugin_file ) ) {
		// built-in plugin under modules/waiting (3rd priority)
		$ret = array(
			'plugin_path' => $builtin_plugin_file ,
			'langfile_path' => '' ,
			'func' => 'b_waiting_'.$dirname ,
			'type' => 'built-in' ,
		) ;
	} else {
		$ret = array() ;
	}

	return $ret ;
}


?>