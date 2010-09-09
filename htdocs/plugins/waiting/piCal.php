<?php
function b_waiting_piCal() {
	$xoopsDB =& icms_db_Factory::instance();
	$block = array();

	//piCal
	$result = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("pical_event")." WHERE admission<1 AND (rrule_pid=0 OR rrule_pid=id)");
	if ($result) {
		$block['adminlink'] = ICMS_URL."/modules/piCal/admin/admission.php";
		list($block['pendingnum']) = $xoopsDB->fetchRow($result);
		$block['lang_linkname'] = _PI_WAITING_EVENTS ;
	}

	return $block;
}
?>