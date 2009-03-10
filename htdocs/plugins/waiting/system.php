<?php
function b_waiting_system(){
	$xoopsDB =& Database::getInstance();
	$ret = array() ;

	// comments
	$block = array();
	$result = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("xoopscomments")." WHERE com_status=1");
	if ( $result ) {
		$block['adminlink'] = XOOPS_URL."/modules/system/admin.php?module=0&status=1&fct=comments" ;
		list($block['pendingnum']) = $xoopsDB->fetchRow($result);
		$block['lang_linkname'] = _PI_WAITING_COMMENTS ;
	}
	$ret[] = $block ;

	// Inactive Users
	$block = array();
	$result = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("users")." WHERE level=0");
	if ( $result ) {
		$block['adminlink'] = XOOPS_URL."/modules/system/admin.php?fct=findusers" ;
		list($block['pendingnum']) = $xoopsDB->fetchRow($result);
		$block['lang_linkname'] = _PI_WAITING_INACTIVE_USERS ;
	}
	$ret[] = $block;

	return $ret ;
}
?>