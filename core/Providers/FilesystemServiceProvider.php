<?php

namespace ImpressCMS\Core\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

/**
 * Filesystem service provider
 */
class FilesystemServiceProvider extends AbstractServiceProvider
{

	/**
	 * @inheritdoc
	 */
	protected $provides = [
		'filesystem.root',
		'filesystem.cache',
		'filesystem.modules',
		'filesystem.uploads',
		'filesystem.themes',
		'filesystem.public',
		'filesystem.compiled'
	];

	/**
	 * @inheritdoc
	 */
	public function register()
	{
		$this->getContainer()->add('filesystem.root', function () {
			return new Filesystem(
				new Local(ICMS_ROOT_PATH)
			);
		});
		$this->getContainer()->add('filesystem.cache', function () {
			return new Filesystem(
				new Local(ICMS_CACHE_PATH)
			);
		});
		$this->getContainer()->add('filesystem.modules', function () {
			return new Filesystem(
				new Local(ICMS_MODULES_PATH)
			);
		});
		$this->getContainer()->add('filesystem.uploads', function () {
			return new Filesystem(
				new Local(ICMS_UPLOAD_PATH)
			);
		});
		$this->getContainer()->add('filesystem.themes', function () {
			return new Filesystem(
				new Local(ICMS_THEME_PATH)
			);
		});
		$this->getContainer()->add('filesystem.public', function () {
			return new Filesystem(
				new Local(ICMS_PUBLIC_PATH)
			);
		});
		$this->getContainer()->add('filesystem.compiled', function () {
			return new Filesystem(
				new Local(ICMS_COMPILE_PATH)
			);
		});
	}

}