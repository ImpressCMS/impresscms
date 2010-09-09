<?php
function b_waiting_articles() {
	$xoopsDB =& icms_db_Factory::instance();
	$block = array();

	$result = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("articles_main")." WHERE art_validated = 0");
	if ($result) {
		$block['adminlink'] = ICMS_URL."/modules/articles/admin/validate.php" ;
		list($block['pendingnum']) = $xoopsDB->fetchRow($result);
		$block['lang_linkname'] = _PI_WAITING_SUBMITTED ;
	}

	return $block;
}
?>