<?php

namespace ImpressCMS\Core\Extensions\SetupSteps\Module\Install;

use Exception;
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

			$output->msg(_MD_AM_COPY_ASSETS_COPYING, $assetPath);

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

				$path = realpath(ICMS_ROOT_PATH . '/' . $path);
				if (!str_starts_with($path, ICMS_ROOT_PATH . '/vendor/')) {
					throw new Exception('Asset path for vendor can\'t be outside vendor path');
				}

				foreach ($this->readAssetData($path) as $filename => $fs) {
					yield $filename => $fs;
				}
				continue;
			}
			$path = realpath(ICMS_MODULES_PATH . '/' . $moduleDir . '/' . $path) . '/';
			if (!str_starts_with($path, ICMS_MODULES_PATH . '/' . $moduleDir . '/')) {
				throw new Exception('Asset path for module can\'t be outside module path');
			}
			foreach ($this->readAssetData($path) as $filename => $fs) {
				yield ($moduleDir . '/' . $filename) => $fs;
			}
		}
	}

	/**
	 * Read asset from path data
	 *
	 * @return Generator|null
	 *
	 * @var string $path Read asset data
	 */
	protected function readAssetData(string $path): ?Generator
	{
		if (!is_dir($path)) {
			yield $path => fopen($path, 'r');
		} elseif (is_dir($path)) {
			/**
			 * @var SplFileInfo $fileInfo
			 */
			foreach ((new RecursiveDirectoryIterator($path)) as $fileInfo) {
				if (($fileInfo->getFilename()[0] === '.') || $fileInfo->isDir() || (in_array(strtolower($fileInfo->getExtension()), ['php', 'htm', 'html', 'tpl'], true))) {
					continue;
				}
				yield $fileInfo->getFilename() => fopen($fileInfo->getRealPath(), 'r');

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

			if ($fileSystemItem['type'] !== 'file') {
				continue;
			}
			if (in_array($fileSystemItem['extension'], ['php', 'htm', 'html', 'tpl', 'yml', 'md', '', 'json'], true)) {
				continue;
			}

			if (
				(($fileSystemItem['extension'] === 'css') && ($fileSystemItem['dirname'] ===$moduleDirname)) ||
				(strpos($fileSystemItem['path'],$moduleDirname . '/images/') === 0) ||
				(strpos($fileSystemItem['path'],$moduleDirname . '/css/') === 0) ||
				(strpos($fileSystemItem['path'],$moduleDirname . '/js/') === 0) ||
				(strpos($fileSystemItem['path'],$moduleDirname . '/themes/') === 0)
			) {
				yield $fileSystemItem['path'] => $mf->readStream($fileSystemItem['path']);
			}
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
