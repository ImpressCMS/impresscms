<?php

/**
* $Id: header.php,v 1.1 2007/06/05 18:45:06 marcan Exp $
* Module: NotFound
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

include_once ('../mainfile.php');
require_once XOOPS_ROOT_PATH.'/header.php';

$myts = MyTextSanitizer::getInstance();

$language_file = XOOPS_ROOT_PATH . "/notfound/language/" . $xoopsConfig['language'] . "/main.php";
if (!file_exists($language_file)) {
	$language_file = XOOPS_ROOT_PATH . "/notfound/language/english/main.php";
}
include_once($language_file);

$xoopsTpl->assign("xoops_module_header", '<link rel="stylesheet" type="text/css" href="notfound.css" />');

if (method_exists($myts, 'formatForML')) {
	$siteName = $myts->formatForML($xoopsConfig['sitename']);
} else {
	$siteName = $xoopsConfig['sitename'];
}

$xoopsTpl->assign('not_found_contact', sprintf(_NOTFOUND_CONTACT, $xoopsConfig['adminmail']));
$xoopsTpl->assign("ref_smartfactory", "The NotFound script is developed by The SmartFactory (http://www.smartfactory.ca), a division of InBox Solutions (http://www.inboxsolutions.net)");
?>