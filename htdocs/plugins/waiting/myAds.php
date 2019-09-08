<?php
function b_waiting_myAds()
{
    $block = [];

    $result = icms::$xoopsDB->query('SELECT COUNT(*) FROM ' . icms::$xoopsDB->prefix('ann_annonces') . " WHERE valid='No'");
    if ($result) {
        $block['adminlink'] = ICMS_URL . '/modules/myAds/admin/index.php';
        list($block['pendingnum']) = icms::$xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_WAITINGS ;
    }

    return $block;
}
