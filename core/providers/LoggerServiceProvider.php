<?php

namespace ImpressCMS\Core\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use icms_core_Logger;

/**
 * Logger service provider
 */
class LoggerServiceProvider extends AbstractServiceProvider
{

	/**
	 * @inheritdoc
	 */
	protected $provides = [
		'logger'
	];

	/**
	 * @inheritdoc
	 */
	public function register()
	{
		$this->getContainer()->add('logger', function () {
			$instance = new icms_core_Logger();
			// Always catch errors, for security reasons
			error_reporting(E_ALL);
			ini_set('display_errors', 1);
			set_error_handler(array($instance, 'handleError'));
			set_exception_handler(array($instance, 'handleException'));
			return $instance;
		});
	}

}