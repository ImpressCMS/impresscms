<?php
/**
* Social Bookmarks
*
* System tool that allow's you to bookmark and share your interesting pageswith other people
* Some parts of this tool is based on social_bookmarks module.
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		Systemblocks
* @since		1.2
* @author		Sina Asghari (AKA stranger) <stranger@impresscms.ir>
* @author		Rene Sato (AKA sato-san) <www.impresscms.de>
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) {
    die("ICMS root path not defined");
}

/**
* Shows the social bookmarks
*
* @param array $options Array for options
* @return array $block The block array
*/
function b_social_bookmarks($options){
	include_once ICMS_ROOT_PATH . '/kernel/icmsaddto.php';
	$icmsaddto = new IcmsAddTo($options[0]);
	$block = $icmsaddto->renderForBlock();
	return $block;
}

/**
* Shows the edit options for the social bookmarks
*
* @param array $options Shows the options for the social bookmarks edit form
* @return string $form The rendered edit form HTML string
*/
function b_social_bookmarks_edit($options)
{
	include_once ICMS_ROOT_PATH . "/class/xoopsformloader.php";

	$form = '';

	$layout_select = new XoopsFormSelect(_MB_SYSTEM_ADDTO_LAYOUT, 'options[]', $options[0]);
	$layout_select->addOption(0, _MB_SYSTEM_ADDTO_LAYOUT_OPTION0);
	$layout_select->addOption(1, _MB_SYSTEM_ADDTO_LAYOUT_OPTION1);
	$layout_select->addOption(2, _MB_SYSTEM_ADDTO_LAYOUT_OPTION2);
	$layout_select->addOption(3, _MB_SYSTEM_ADDTO_LAYOUT_OPTION3);
	$form .= $layout_select->getCaption() . ' ' . $layout_select->render() . '<br />';

	return $form;
}
?>