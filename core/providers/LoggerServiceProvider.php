<?php

namespace ImpressCMS\Core\Providers;

use icms_core_Logger;
use League\Container\ServiceProvider\AbstractServiceProvider;
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
		$this->getContainer()->add('logger', icms_core_Logger::instance());
	}

}