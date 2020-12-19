<?php

namespace ImpressCMS\Core\Extensions\Smarty\Functions;

use Aura\Session\Session;
use icms;
use icms_db_criteria_Compo;
use icms_db_criteria_Item;
use ImpressCMS\Core\Extensions\Smarty\SmartyFunctionExtensionInterface;

class XOInboxCountFunction implements SmartyFunctionExtensionInterface
{

	/**
	 * @inheritDoc
	 */
	public function execute($params, &$smarty)
	{
		if (!isset(icms::$user) || !is_object(icms::$user)) {
			return;
		}
		$time = time();
		/**
		 * @var Session $session
		 */
		$session = icms::getInstance()->get('session');
		$inboxCounterSegment = $session->getSegment('inbox');
		if ((int)$inboxCounterSegment->get('expire', 0) > $time) {
			$count = (int)$inboxCounterSegment->get('count', 0);
		} else {
			$pm_handler = icms::handler('icms_data_privmessage');
			$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('read_msg', 0));
			$criteria->add(new icms_db_criteria_Item('to_userid', icms::$user->getVar('uid')));
			$count = (int)$pm_handler->getCount($criteria);
			$inboxCounterSegment->set('count', $count);
			$inboxCounterSegment->set('expire', $time + 60);
		}
		if (!@empty($params['assign'])) {
			$smarty->assign($params['assign'], $count);
		} else {
			echo $count;
		}
	}

	/**
	 * @inheritDoc
	 */
	public function getName(): string
	{
		return 'xoInboxCount';
	}
}