<?php

namespace ImpressCMS\Core\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use icms_preload_Handler as PreloadHandler;

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
		$this->getContainer()->add('preload', function () {
			$preload = PreloadHandler::getInstance();
			$preload->triggerEvent('startCoreBoot');
			return $preload;
		});
	}
}