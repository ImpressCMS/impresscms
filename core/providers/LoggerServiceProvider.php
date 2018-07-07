<?php

namespace ImpressCMS\Core\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use icms_core_Logger;
use icms_Event;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

/**
 * Logger service provider
 */
class LoggerServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
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

	}

	/**
	 * @inheritDoc
	 */
	public function boot()
	{
		$this->getContainer()->add('logger', function () {
			return icms_core_Logger::instance();
		});
	}

}