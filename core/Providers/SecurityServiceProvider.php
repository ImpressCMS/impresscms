<?php

namespace ImpressCMS\Core\Providers;

use ImpressCMS\Core\Event;
use ImpressCMS\Core\Security\RequestSecurity;
use icms_core_Security;
use League\Container\ServiceProvider\AbstractServiceProvider;

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
			$instance = new RequestSecurity();

      if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] != 'POST' || !$instance->checkReferer(XOOPS_DB_CHKREF))) {

				define('XOOPS_DB_PROXY', 1);
			}
			return $instance;
		});
	}
}