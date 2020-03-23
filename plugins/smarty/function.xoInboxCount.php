<?php
function smarty_function_xoInboxCount( $params, &$smarty ) {
	if (!isset(icms::$user) || !is_object(icms::$user)) {
		return;
	}
	$time = time();
	/**
	 * @var \Aura\Session\Session $session
	 */
	$session = \icms::getInstance()->get('session');
	$inboxCounterSegment = $session->getSegment('inbox');
	if ( (int)$inboxCounterSegment->get('expire', 0) > $time) {
		$count = (int)$inboxCounterSegment->get('count', 0);
	} else {
        $pm_handler = icms::handler('icms_data_privmessage');
        $criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('read_msg', 0));
        $criteria->add(new icms_db_criteria_Item('to_userid', icms::$user->getVar('uid')));
        $count = (int)$pm_handler->getCount($criteria) ;
        $inboxCounterSegment->set('count', $count);
		$inboxCounterSegment->set('expire',  $time + 60);
	}
	if (!@empty($params['assign'])) {
		$smarty->assign($params['assign'], $count);
	} else {
		echo $count;
	}
}