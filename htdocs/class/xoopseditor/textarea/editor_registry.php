<?php
/**
 * Editor framework for XOOPS
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since		1.00
 * @version		$Id: editor_registry.php,v 1.1 2007/06/05 14:44:27 marcan Exp $
 * @package		xoopseditor
 */
/**
 * XOOPS editor registry
 *
 * @author	    phppp (D.J.)
 * @copyright	copyright (c) 2005 XOOPS.org
 *
 */
global $xoopsConfig;

$current_path = __FILE__;
if ( DIRECTORY_SEPARATOR != "/" ) $current_path = str_replace( strpos( $current_path, "\\\\", 2 ) ? "\\\\" : DIRECTORY_SEPARATOR, "/", $current_path);
$root_path = dirname($current_path);

$xoopsConfig['language'] = preg_replace("/[^a-z0-9_\-]/i", "", $xoopsConfig['language']);
if(!@include_once($root_path."/language/".$xoopsConfig['language'].".php")){
	include_once($root_path."/language/english.php");
}

$config = array(
		//"name"	=>	"textarea", // the name used as unique identifier
		"class"	=>	"FormTextArea",
		"file"	=>	$root_path."/textarea.php",
		"title"	=>	_XOOPS_EDITOR_TEXTAREA, // display to end user
		"order"	=>	2, // 0 will disable the editor
		"nohtml"=>	1 // For forms that have "dohtml" disabled
	);
?>