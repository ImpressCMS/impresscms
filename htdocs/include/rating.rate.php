<?php

/**
* $Id: rating.rate.php 159 2007-12-17 16:44:05Z malanciault $
* Module: SmartRental
* Author: The SmartFactory <www.icmsfactory.ca>
* Licence: GNU
*/
if (!defined("XOOPS_ROOT_PATH")) {
	die("XOOPS root path not defined");
}

include_once(ICMS_ROOT_PATH . "/modules/system/admin/rating/class/rating.php");

icms_loadLanguageFile('system', 'rating', true);

$module_dirname = $icmsModule->dirname();

// Retreive the IcmsObject Rating plugin for the current module if it exists
$icms_rating_handler = icms_getModuleHandler('rating', 'system');
$icms_plugin_handler = new IcmsPluginsHandler();
$pluginObj = $icms_plugin_handler->getPlugin($module_dirname);
if ($pluginObj) {
	$rating_item = $pluginObj->getItem();
	 if ($rating_item) {
		$rating_itemid = $pluginObj->getItemIdForItem($rating_item);
		$stats = $icms_rating_handler->getRatingAverageByItemId($rating_itemid, $module_dirname, $rating_item);
		$icmsTpl->assign('icms_rating_stats_total', $stats['sum']);
		$icmsTpl->assign('icms_rating_stats_average', $stats['average']);
		$icmsTpl->assign('icms_rating_item', $rating_item);
		if(is_object($icmsUser)){
			$ratingObj = $icms_rating_handler->already_rated($rating_item, $rating_itemid, $module_dirname, $icmsUser->getVar('uid'));
			$icmsTpl->assign('icms_user_can_rate', true);
		}
		if(isset($ratingObj) && is_object($ratingObj)){
			$icmsTpl->assign('icms_user_rate', $ratingObj->getVar('rate'));
			$icmsTpl->assign('icms_rated', true);
		}else{
			$icmsTpl->assign('icms_rating_dirname', $module_dirname);
			$icmsTpl->assign('icms_rating_itemid', $rating_itemid);
			$urls = icms_getCurrentUrls();
			$icmsTpl->assign('icms_rating_current_page', $urls['full']);
/*			if(isset($xoTheme) && is_object($xoTheme)){
				$xoTheme->addStylesheet(ICMS_URL . '/module.css');
			}else{
				//probleme d'inclusion de css apres le flashplayer. Style plac dans css du theme
				//$icmsTpl->assign('icms_css',"<link rel='stylesheet' type='text/css' href='".XOOPS_URL."/modules/icms/module.css' />");
			}
*/
		}
	 }
}

if (isset($_POST['icms_rating_submit'])) {
	// The rating form has just been posted. Let's save the info
	$ratingObj = $icms_rating_handler->create();
	$ratingObj->setVar('dirname', $module_dirname);
	$ratingObj->setVar('item', $rating_item);
	$ratingObj->setVar('itemid', $rating_itemid);
	$ratingObj->setVar('uid', $icmsUser->getVar('uid'));
	$ratingObj->setVar('date', time());
	$ratingObj->setVar('rate', $_POST['icms_rating_value']);
	if (!$icms_rating_handler->insert($ratingObj)) {
		if ($xoopsDB->errno() == 1062) {
			$message = _CO_ICMS_RATING_DUPLICATE_ENTRY;
		} else {
			$message = _CO_ICMS_RATING_ERROR;
		}
	} else {
		$message = _CO_ICMS_RATING_SUCCESS;
	}
	redirect_header('', 3, $message);
	exit;
}

?>