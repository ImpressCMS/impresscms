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
	public function register()
	{

	}

	/**
	 * @inheritDoc
	 */
	public function boot()
	{
		$this->getContainer()->add(PreloadHandler::class, function () {
			$preload = PreloadHandler::getInstance();
			$preload->triggerEvent('startCoreBoot');
			return $preload;
		});
	}
}