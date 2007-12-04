<?php
/**
 * Editor framework for XOOPS
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since		1.00
 * @version		$Id: sampleform.inc.php,v 1.1 2007/06/05 14:43:47 marcan Exp $
 * @package		xoopseditor
 */
/**
 * XOOPS editor usage example
 *
 * @author	    phppp (D.J.)
 * @copyright	copyright (c) 2005 XOOPS.org
 *
 */


if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

/*
 * Edit form with selected editor
 */
$sample_form = new XoopsThemeForm('', 'sample_form', "action.php");
$sample_form->setExtra('enctype="multipart/form-data"');

// Not required but for user-friendly concern
$editor = !empty($_REQUEST['editor'])?$_REQUEST['editor']:"";
if(!empty($editor)){
	setcookie("editor", $editor); // save to cookie
}else
// Or use user pre-selected editor through profile
if(is_object($xoopsUser)){
	$editor =@ $xoopsUser->getVar("editor"); // Need set through user profile
}

// Add the editor selection box
// If dohtml is disabled, set $noHtml = true
$sample_form->addElement(new XoopsFormSelectEditor($sample_form, "editor", $editor, $noHtml = false));

// options for the editor
//required configs
$options['name'] ='required_element';
$options['value'] = empty($_REQUEST['message'])? "" : $_REQUEST['message'];
//optional configs
$options['rows'] = 25; // default value = 5
$options['cols'] = 60; // default value = 50
$options['width'] = '100%'; // default value = 100%
$options['height'] = '400px'; // default value = 400px

// "textarea": if the selected editor with name of $editor can not be created, the editor "textarea" will be used
// if no $onFailure is set, then the first available editor will be used
// If dohtml is disabled, set $noHtml to true
$sample_form->addElement(new XoopsFormEditor(_MD_MESSAGEC, $editor, $editor_configs, $nohtml = false, $onfailure = "textarea"), true);

$sample_form->addElement(new XoopsFormText("SOME REQUIRED ELEMENTS", "required_element2", 50, 255, $required_element2), true);

$sample_form->addElement(new XoopsFormButton('', 'save', _SUBMIT, "submit"));

$sample_form->display();
?>
