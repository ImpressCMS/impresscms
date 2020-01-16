<?php

namespace ImpressCMS\Core\Providers;

use icms_preload_Handler as PreloadHandler;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

/**
 * Preload service provider
 */
class PreloadServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{

	/**
	 * @inheritdoc
	 */
	protected $provides = [
		'preload'
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
		$preload = PreloadHandler::getInstance();
		$preload->triggerEvent('startCoreBoot');

		$this->getContainer()->add('preload', $preload);
	}
}