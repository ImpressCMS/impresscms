<?php

namespace ImpressCMS\Core\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;

/**
 * Filesystem service provider
 */
class FilesystemServiceProvider extends AbstractServiceProvider
{

	/**
	 * @inheritdoc
	 */
	protected $provides = [
		'filesystem'
	];

	/**
	 * @inheritdoc
	 */
	public function register()
	{
		$this->getContainer()->add('filesystem', function () {
			return new MountManager([
				'root' => new Filesystem(
					new Local(ICMS_ROOT_PATH)
				),
				'cache' => new Filesystem(
					new Local(ICMS_CACHE_PATH)
				),
				'modules' => new Filesystem(
					new Local(ICMS_MODULES_PATH)
				),
				'uploads' => new Filesystem(
					new Local(ICMS_UPLOAD_PATH)
				),
				'themes' => new Filesystem(
					new Local(ICMS_THEME_PATH)
				)
			]);
		});
	}

}