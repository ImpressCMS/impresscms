<?php
//
// wf-sections ext waiting plugin
// author: karedokx <karedokx@yahoo.com> 15-Apr-2005
//
function b_waiting_wfsection() {
	$ret = array();

	// wf-section articles - new
	$block = array();
	$result = icms::$xoopsDB->query("SELECT COUNT(*) FROM " . icms::$xoopsDB->prefix("wfs_article") . " WHERE published=0");
	if ($result) {
		$block['adminlink'] = ICMS_URL . "/modules/wfsection/admin/allarticles.php?action=submitted";
		list($block['pendingnum']) = icms::$xoopsDB->fetchRow($result);
		$block['lang_linkname'] = _PI_WAITING_WAITINGS;
	}
	$ret[] = $block;

	// wf-section articles - modified
	$block = array();
	$result = icms::$xoopsDB->query("SELECT COUNT(*) FROM " . icms::$xoopsDB->prefix("wfs_article_mod") . "");
	if ($result) {
		$block['adminlink'] = ICMS_URL . "/modules/wfsection/admin/modified.php";
		list($block['pendingnum']) = icms::$xoopsDB->fetchRow($result);
		$block['lang_linkname'] = _PI_WAITING_MODREQS;
	}
	$ret[] = $block;

	return $ret;
}
