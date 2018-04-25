<?php

namespace ImpressCMS\Core\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use icms_core_Security;
use icms_Event;

/**
 * Security service provider
 */
class SecurityServiceProvider extends AbstractServiceProvider
{

	/**
	 * @inheritdoc
	 */
	protected $provides = [
		'security'
	];

	/**
	 * @inheritdoc
	 */
	public function register()
	{
		$this->getContainer()->add('security', function () {
			$instance = new icms_core_Security();
			$instance->checkSuperglobals();
			if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] != 'POST' || !$instance->checkReferer(XOOPS_DB_CHKREF))) {
				define('XOOPS_DB_PROXY', 1);
			}
			icms_Event::attach('icms', 'loadService-config', array($instance, 'checkBadips'));
			return $instance;
		});
	}
}