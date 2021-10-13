<?php

namespace ImpressCMS\Core\Extensions\SetupSteps\Module\Install;

use Exception;
use FilesystemIterator;
use Generator;
use icms_module_Object;
use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Extensions\SetupSteps\SetupStepInterface;
use ImpressCMS\Core\Models\File;
use ImpressCMS\Core\Models\Module;
use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\Filesystem;
use RecursiveDirectoryIterator;
use SplFileInfo;

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
		$mm->deleteDir('modules/' . $module->dirname);
		$mm->createDir('modules/' . $module->dirname);

		foreach ($this->getModuleAssetToCopy($module->dirname) as $assetPath => $assetContent) {
			$output->msg(_MD_AM_COPY_ASSETS_COPYING, 'modules/' . $assetPath);
			$mm->writeStream(
				'modules/' . $assetPath,
				$assetContent
			);
		}

		foreach ($this->getDefinedAssets((array)$module->getInfo('assets'), $module->dirname) as $assetPath => $assetContent) {
			$output->msg(_MD_AM_COPY_ASSETS_COPYING, 'modules/' .  $assetPath);
			if ($mm->has('modules/' . $assetPath)) {
				$mm->delete('modules/' . $assetPath);
			}
			$mm->writeStream(
				'modules/' . $assetPath,
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
				$path = realpath(ICMS_ROOT_PATH . '/' . $path);
				if (!str_starts_with($path, ICMS_ROOT_PATH . '/vendor/')) {
					throw new Exception('Asset path for vendor can\'t be outside vendor path');
				}
				/**
				 * @var Filesystem $fs
				 */
				$fs = $this->container->get('filesystem.root');
				foreach ($fs->listContents($originalPath, true) as $fileSystemItem) {
					if ($fileSystemItem['type'] !== 'file') {
						continue;
					}
					yield ($moduleDir . '/'.$originalPath.'/' . $fileSystemItem['path']) => $fs->readStream($fileSystemItem['path']);
				}
				continue;
			}

			/**
			 * @var Filesystem $mf
			 */
			$mf = $this->container->get('filesystem.modules');
			foreach ($mf->listContents($moduleDir . '/' . $path, true) as $fileSystemItem) {
				if ($fileSystemItem['type'] !== 'file') {
					continue;
				}
				yield $fileSystemItem['path'] => $mf->readStream($fileSystemItem['path']);
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
			if ($fileSystemItem['type'] !== 'file' || $fileSystemItem['basename'][0] === '.' || $fileSystemItem['basename'] === 'LICENSE') {
				continue;
			}
			if (in_array($fileSystemItem['extension'], ['php', 'htm', 'html', 'tpl', 'yml', 'md', '', 'json'], true)) {
				continue;
			}
			yield $fileSystemItem['path'] => $mf->readStream($fileSystemItem['path']);
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
