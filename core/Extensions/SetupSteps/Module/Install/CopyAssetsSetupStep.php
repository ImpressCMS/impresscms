<?php

namespace ImpressCMS\Core\Extensions\SetupSteps\Module\Install;

use Exception;
use Generator;
use icms_module_Object;
use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Extensions\SetupSteps\SetupStepInterface;
use ImpressCMS\Core\Models\Module;
use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use League\Flysystem\FileAttributes;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\Filesystem;
use League\Flysystem\StorageAttributes;

/**
 * Copies module assets to public path
 *
 * @package ImpressCMS\Core\SetupSteps\Module\Install
 */
class CopyAssetsSetupStep implements SetupStepInterface, ContainerAwareInterface
{
	use ContainerAwareTrait;

	/**
	 * @inheritDoc
	 */
	public function execute(Module $module, OutputDecorator $output, ...$params): bool
	{
		/**
		 * @var Filesystem $mm
		 */
		$mm = $this->container->get('filesystem.public');
		$output->info(_MD_AM_COPY_ASSETS_INFO);
		$output->incrIndent();
		$output->msg(_MD_AM_COPY_ASSETS_DELETE_OLD);
		$mm->deleteDirectory('modules/' . $module->dirname);
		$mm->createDirectory('modules/' . $module->dirname);

		foreach ($this->getModuleAssetToCopy($module->dirname) as $assetPath => $assetContent) {
			$output->msg(_MD_AM_COPY_ASSETS_COPYING, 'modules/' . $assetPath);
			$mm->writeStream(
				'modules/' . $assetPath,
				$assetContent
			);
		}

		foreach ($this->getDefinedAssets((array)$module->getInfo('assets'), $module->dirname) as $assetPath => $assetContent) {
			$output->msg(_MD_AM_COPY_ASSETS_COPYING, $assetPath);
			if ($mm->fileExists($assetPath)) {
				$mm->delete($assetPath);
			}
			$mm->writeStream(
				$assetPath,
				$assetContent
			);
		}


		$output->decrIndent();

		return true;
	}

	/**
	 * Reads defined assets data for copy
	 *
	 * @param array $assets Assets list
	 * @param string $moduleDir Module dir
	 *
	 * @return Generator
	 *
	 * @throws Exception
	 */
	protected function getDefinedAssets(array $assets, string $moduleDir): ?Generator
	{
		foreach ($assets as $path) {
			if (str_starts_with($path, 'vendor/')) {
				$originalPath = trim($path, '/');
				$path = ICMS_ROOT_PATH . '/' . $path;
				if (!str_starts_with($path, ICMS_ROOT_PATH . '/vendor/')) {
					throw new Exception('Asset path for vendor can\'t be outside vendor path (' . json_encode([$path, ICMS_ROOT_PATH . '/vendor/']) . ')');
				}
				/**
				 * @var Filesystem $fs
				 */
				$fs = $this->container->get('filesystem.root');
				/**
				 * @var StorageAttributes $fileSystemItem
				 */
				foreach ($fs->listContents($originalPath, true) as $fileSystemItem) {
					if ($fileSystemItem->isFile()) {
						continue;
					}
					$relativePath = $fileSystemItem->path();
					yield 'modules/' . $moduleDir . '/' . $relativePath => $fs->readStream($relativePath);
				}
				continue;
			}

			/**
			 * @var Filesystem $mf
			 */
			$mf = $this->container->get('filesystem.modules');
			foreach ($mf->listContents($moduleDir . '/' . $path, true) as $fileSystemItem) {
				if (!($fileSystemItem instanceof FileAttributes)) {
					continue;
				}
				$relativePath = $fileSystemItem->path();
				$ext = pathinfo($relativePath, PATHINFO_EXTENSION);
				if (in_array($ext, ['php', 'htm', 'html', 'tpl', 'yml', 'md', '', 'json'], true)) {
					continue;
				}
				yield $relativePath => $mf->readStream($relativePath);
			}
		}
	}

	/**
	 * Gets assets from module to copy
	 *
	 * @param string $moduleDirname
	 *
	 * @return Generator
	 *
	 * @throws FileNotFoundException
	 */
	protected function getModuleAssetToCopy(string $moduleDirname): ?Generator
	{
		/**
		 * @var Filesystem $mf
		 */
		$mf = $this->container->get('filesystem.modules');
		foreach ($mf->listContents($moduleDirname, true) as $fileSystemItem) {
			if (!($fileSystemItem instanceof FileAttributes)) {
				continue;
			}
			$relativePath = $fileSystemItem->path();
			$basename = basename($relativePath);
			if ($basename[0] === '.' || $basename === 'LICENSE') {
				continue;
			}
			$ext = pathinfo($relativePath, PATHINFO_EXTENSION);
			if (in_array($ext, ['php', 'htm', 'html', 'tpl', 'yml', 'md', '', 'json'], true)) {
				continue;
			}
			yield $relativePath => $mf->readStream($relativePath);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function getPriority(): int
	{
		return 100;
	}
}
