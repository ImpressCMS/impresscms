<?php
function b_waiting_tutorials() {
	$xoopsDB =& Database::getInstance();
	$block = array();

	// tutorials
	$myts =& icms_core_Textsanitizer::getInstance();

	$result = $xoopsDB->query("select count(*) from ".$xoopsDB->prefix("tutorials")." WHERE status=0 or status=2 order by date");
	if ($result) {
		$block['adminlink'] = ICMS_URL."/modules/tutorials/admin/index.php" ;
		list($block['pendingnum']) = $xoopsDB->fetchRow($result);
		$block['lang_linkname'] = _PI_WAITING_WAITINGS ;
	}

	return $block;
}

?>