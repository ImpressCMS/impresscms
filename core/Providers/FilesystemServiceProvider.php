<?php

namespace ImpressCMS\Core\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;

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
		'filesystem.compiled',
		'filesystem.libraries',
	];

	/**
	 * @inheritdoc
	 */
	public function register()
	{
		$this->getContainer()->add('filesystem.root', function() {
			return new Filesystem(
				new LocalFilesystemAdapter(ICMS_ROOT_PATH)
			);
		});
		$this->getContainer()->add('filesystem.cache', function() {
			return new Filesystem(
				new LocalFilesystemAdapter(ICMS_CACHE_PATH)
			);
		});
		$this->getContainer()->add('filesystem.modules', function() {
			return new Filesystem(
				new LocalFilesystemAdapter(ICMS_MODULES_PATH)
			);
		});
		$this->getContainer()->add('filesystem.uploads', function() {
			return new Filesystem(
				new LocalFilesystemAdapter(ICMS_UPLOAD_PATH)
			);
		});
		$this->getContainer()->add('filesystem.themes', function() {
			return new Filesystem(
				new LocalFilesystemAdapter(ICMS_THEME_PATH)
			);
		});
		$this->getContainer()->add('filesystem.public', function() {
			return new Filesystem(
				new LocalFilesystemAdapter(ICMS_PUBLIC_PATH)
			);
		});
		$this->getContainer()->add('filesystem.compiled', function() {
			return new Filesystem(
				new LocalFilesystemAdapter(ICMS_COMPILE_PATH)
			);
		});
		$this->getContainer()->add('filesystem.libraries', function() {
			return new Filesystem(
				new LocalFilesystemAdapter(ICMS_LIBRARIES_PATH)
			);
		});
	}

}