<?php 
/*
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003-2006 Frederico Caldeira Knabben
 * 
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 * 
 * For further information visit:
 * 		http://www.fckeditor.net/
 * 
 * "Support Open Source software. What about a donation today?"
 * 
 * File Name: config.php
 * 	Configuration file for the File Manager Connector for PHP.
 * 
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 */

global $Config ;


if(!function_exists("get_fckeditor_root_path")){
	function get_fckeditor_root_path()
	{
		static $fckeditor_root_path;
		if(!isset($fckeditor_root_path)){
			$current_path = dirname(__FILE__);
			if ( DIRECTORY_SEPARATOR != "/" ) $current_path = str_replace( DIRECTORY_SEPARATOR, "/", $current_path);
			$fckeditor_root_path = substr($current_path, 0, strpos($current_path, basename(dirname(dirname(__FILE__)))."/editor"/*"FCKeditor/editor"*/));
		}
		return $fckeditor_root_path;
	}
}

$file = get_fckeditor_root_path()."/xoopseditor.inc.php";
include $file;
if(!defined("XOOPS_UPLOAD_PATH")){
	die("Path error!");
}

if(!defined("XOOPS_FCK_FOLDER") || !$uploadPath = preg_replace("/[^a-z0-9_\-]/i", "", XOOPS_FCK_FOLDER) ){
	$uploadPath = "fckeditor";
}

if(is_object($GLOBALS["xoopsLogger"])) {
    $GLOBALS["xoopsLogger"]->activated = false;
}

// SECURITY: You must explicitelly enable this "connector". (Set it to "true").
$Config['Enabled'] = true ;

// Path to user files relative to the document root.
$Config['UserFilesPath'] = XOOPS_UPLOAD_URL."/".$uploadPath."/" ;

// Fill the following value it you prefer to specify the absolute path for the
// user files directory. Usefull if you are using a virtual directory, symbolic
// link or alias. Examples: 'C:\\MySite\\UserFiles\\' or '/root/mysite/UserFiles/'.
// Attention: The above 'UserFilesPath' must point to the same directory.
$Config['UserFilesAbsolutePath'] = XOOPS_UPLOAD_PATH."/".$uploadPath."/" ;

// Due to security issues with Apache modules, it is reccomended to leave the
// following setting enabled.
$Config['ForceSingleExtension'] = true ;

$Config['AllowedExtensions']['File']	= array() ;
$Config['DeniedExtensions']['File']		= array('php','php2','php3','php4','php5','phtml','pwml','inc','asp','aspx','ascx','jsp','cfm','cfc','pl','bat','exe','com','dll','vbs','js','reg','cgi') ;

$Config['AllowedExtensions']['Image']	= array('jpg','gif','jpeg','png') ;
$Config['DeniedExtensions']['Image']	= array() ;

$Config['AllowedExtensions']['Flash']	= array('swf','fla') ;
$Config['DeniedExtensions']['Flash']	= array() ;

$Config['AllowedExtensions']['Media']	= array('swf','fla','jpg','gif','jpeg','png','avi','mpg','mpeg') ;
$Config['DeniedExtensions']['Media']	= array() ;

?>