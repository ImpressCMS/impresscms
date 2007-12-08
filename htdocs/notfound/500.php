<?php

/**
* $Id: 500.php,v 1.1 2007/06/05 18:45:06 marcan Exp $
* Module: NotFound
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

include_once ('header.php');

$xoopsTpl->assign('xoops_pagetitle', _NOTFOUND_500_PAGE_TITLE);
$xoopsTpl->assign('not_found_text', sprintf(_NOTFOUND_500_TEXT, $siteName));

$xoopsTpl->display(XOOPS_ROOT_PATH . "/notfound/templates/500.html");

require_once XOOPS_ROOT_PATH.'/footer.php';

?>