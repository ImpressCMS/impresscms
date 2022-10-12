<?php

namespace ImpressCMS\Core\Extensions\SetupSteps;

use Exception;
use Generator;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use League\Flysystem\StorageAttributes;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * A trait that helps to read in composer.json defined assets data
 */
trait AddonAssetsTrait
{

	/**
	 * Reads defined assets data for copy
	 *
	 * @param array $assets Assets list
	 * @param string $addonDir Module/Theme dir
	 * @param string $addonType Type (modules or themes)
	 *
	 * @return Generator
	 *
	 * @throws Exception|FilesystemException
	 */
	protected function getDefinedAssets(array $assets, string $addonDir, string $addonType): ?Generator
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
					yield $addonType . '/' . $addonDir . '/' . $relativePath => $fs->readStream($relativePath);
				}
				continue;
			}

			/**
			 * @var Filesystem $mf
			 */
			$mf = $this->container->get('filesystem.' . $addonType);
			foreach ($mf->listContents($addonDir . '/' . $path, true) as $fileSystemItem) {
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
	 * Gets assets from addon to copy
	 *
	 * @param string $addonDir Module/Theme dir
	 * @param string $addonType Type (modules or themes)
	 *
	 * @return Generator
	 *
	 * @throws FileNotFoundException|FilesystemException
	 */
	protected function getAddonAssetToCopy(string $addonDir, string $addonType): ?Generator
	{
		/**
		 * @var Filesystem $mf
		 */
		$mf = $this->container->get('filesystem.' . $addonType);
		foreach ($mf->listContents($addonDir, true) as $fileSystemItem) {
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
	 * Recreate public assets path
	 *
	 * @param string $addonDir Module/Theme dir
	 * @param string $addonType Type (modules or themes)
	 *
	 * @return void
	 *
	 * @throws FilesystemException
	 */
	protected function recreateAssetsPublicFolderPath(string $addonDir, string $addonType): void
	{
		/**
		 *
		 * @var Filesystem $mm
		 */
		$mm = $this->container->get('filesystem.public');

		$mm->deleteDirectory($addonType . '/' . $addonDir);
		$mm->createDirectory($addonType . '/' . $addonDir);
	}

	/**
	 * Copy all addon assets and yield assets name when copying
	 *
	 * @param array $assets Assets list
	 * @param string $addonDir Module/Theme dir
	 * @param string $addonType Type (modules or themes)
	 *
	 * @return Generator
	 * @throws FilesystemException
	 */
	protected function copyAllAssets(array $assets, string $addonDir, string $addonType): Generator
	{
		/**
		 *
		 * @var Filesystem $mm
		 */
		$mm = $this->container->get('filesystem.public');

		$addonAssets = $this->getAddonAssetToCopy($addonDir, $addonType);
		foreach ($addonAssets as $assetPath => $assetContent) {
			$newAssetsPath = $addonType . '/' . $assetPath;
			if ($mm->fileExists($assetPath)) {
				$mm->delete($assetPath);
			}

			$mm->writeStream($newAssetsPath, $assetContent);

			yield $newAssetsPath;
		}

		$definedAssets = $this->getDefinedAssets($assets, $addonDir, $addonType);
		foreach ($definedAssets as $assetPath => $assetContent) {
			if ($mm->fileExists($assetPath)) {
				$mm->delete($assetPath);
			}
			$mm->writeStream($assetPath, $assetContent);

			yield $assetPath;
		}
	}

}