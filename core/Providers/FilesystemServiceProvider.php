<?php

namespace ImpressCMS\Core\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Flysystem\UnixVisibility\PortableVisibilityConverter;
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
	public function register()
		// TODO: make this more compact by using an iterator over the $provides array
	{
		$this->getContainer()->add('filesystem.root', function () {
			return new Filesystem(
				new LocalFilesystemAdapter(ICMS_ROOT_PATH, PortableVisibilityConverter::fromArray([
					'file' => [
						'public' => 0640,
						'private' => 0604,
					],
					'dir' => [
						'public' => 0740,
						'private' => 7604,
					],
				]),LOCK_EX, LocalFilesystemAdapter::SKIP_LINKS)
			);
		});
		$this->getContainer()->add('filesystem.cache', function () {
			return new Filesystem(
				new LocalFilesystemAdapter(ICMS_ROOT_PATH, PortableVisibilityConverter::fromArray([
					'file' => [
						'public' => 0640,
						'private' => 0604,
					],
					'dir' => [
						'public' => 0740,
						'private' => 7604,
					],
				]),LOCK_EX, LocalFilesystemAdapter::SKIP_LINKS)
			);
		});
		$this->getContainer()->add('filesystem.modules', function () {
			return new Filesystem(
				new LocalFilesystemAdapter(ICMS_ROOT_PATH, PortableVisibilityConverter::fromArray([
					'file' => [
						'public' => 0640,
						'private' => 0604,
					],
					'dir' => [
						'public' => 0740,
						'private' => 7604,
					],
				]),LOCK_EX, LocalFilesystemAdapter::SKIP_LINKS)
			);
		});
		$this->getContainer()->add('filesystem.uploads', function () {
			return new Filesystem(
				new LocalFilesystemAdapter(ICMS_ROOT_PATH, PortableVisibilityConverter::fromArray([
					'file' => [
						'public' => 0640,
						'private' => 0604,
					],
					'dir' => [
						'public' => 0740,
						'private' => 7604,
					],
				]),LOCK_EX, LocalFilesystemAdapter::SKIP_LINKS)
			);
		});
		$this->getContainer()->add('filesystem.themes', function () {
			return new Filesystem(
				new LocalFilesystemAdapter(ICMS_ROOT_PATH, PortableVisibilityConverter::fromArray([
					'file' => [
						'public' => 0640,
						'private' => 0604,
					],
					'dir' => [
						'public' => 0740,
						'private' => 7604,
					],
				]),LOCK_EX, LocalFilesystemAdapter::SKIP_LINKS)
			);
		});
		$this->getContainer()->add('filesystem.public', function () {
			return new Filesystem(
				new LocalFilesystemAdapter(ICMS_ROOT_PATH, PortableVisibilityConverter::fromArray([
					'file' => [
						'public' => 0640,
						'private' => 0604,
					],
					'dir' => [
						'public' => 0740,
						'private' => 7604,
					],
				]),LOCK_EX, LocalFilesystemAdapter::SKIP_LINKS)
			);
		});
		$this->getContainer()->add('filesystem.compiled', function () {
			return new Filesystem(
				new LocalFilesystemAdapter(ICMS_ROOT_PATH, PortableVisibilityConverter::fromArray([
					'file' => [
						'public' => 0640,
						'private' => 0604,
					],
					'dir' => [
						'public' => 0740,
						'private' => 7604,
					],
				]),LOCK_EX, LocalFilesystemAdapter::SKIP_LINKS)
			);
		});
		$this->getContainer()->add('filesystem.libraries', function () {
			return new Filesystem(
				new LocalFilesystemAdapter(ICMS_ROOT_PATH, PortableVisibilityConverter::fromArray([
					'file' => [
						'public' => 0640,
						'private' => 0604,
					],
					'dir' => [
						'public' => 0740,
						'private' => 7604,
					],
				]),LOCK_EX, LocalFilesystemAdapter::SKIP_LINKS)
			);
		});
	}

}
