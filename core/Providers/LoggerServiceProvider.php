<?php

namespace ImpressCMS\Core\Providers;

use ImpressCMS\Core\Logger;
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
		$this->getContainer()->add('logger', Logger::instance());
	}

}