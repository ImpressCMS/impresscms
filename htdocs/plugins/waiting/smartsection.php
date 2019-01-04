<?php
/*************************************************************************/
# Waiting Contents Extensible                                            #
# Plugin for module smartsection                                         #
#                                                                        #
# Author                                                                 #
# flying.tux     -   flying.tux@gmail.com                                #
#                                                                        #
# Last modified on 21.04.2005                                            #
/*************************************************************************/
function b_waiting_smartsection() {
	$ret = array() ;

	// smartsection submitted
	$block = array();
	$result = icms::$xoopsDB->query("SELECT COUNT(*) FROM ".icms::$xoopsDB->prefix("smartsection_items")." WHERE status=1");
	if ($result) {
		$block['adminlink'] = ICMS_URL."/modules/smartsection/admin/index.php?statussel=1";
		list($block['pendingnum']) = icms::$xoopsDB->fetchRow($result);
		$block['lang_linkname'] = _PI_WAITING_SUBMITTED;
	}
	$ret[] = $block ;

	return $ret;
}

?>