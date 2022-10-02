<?php

namespace ImpressCMS\Core\Extensions\ExtensionDescriber\Theme;

use Composer\Factory;
use Composer\IO\NullIO;
use ImpressCMS\Core\Extensions\ExtensionDescriber\DescribedItemInfoInterface;
use ImpressCMS\Core\Extensions\ExtensionDescriber\ExtensionDescriberInterface;
use ImpressCMS\Core\Extensions\ExtensionDescriber\ThemeInfo;

/**
 * Describes theme from composer file
 *
 * @package ImpressCMS\Core\Extensions\ExtensionDescriber\Theme
 */
class ImpressCMSComposerThemeDescriber implements ExtensionDescriberInterface
{

	/**
	 * @inheritDoc
	 */
	public function canDescribe(string $path): bool
	{
		return file_exists($path . DIRECTORY_SEPARATOR . 'composer.json');
	}

	/**
	 * @inheritDoc
	 */
	public function describe(string $path): DescribedItemInfoInterface
	{
		chdir($path);
		putenv('COMPOSER_HOME=' . ICMS_STORAGE_PATH . '/composer');
		$composer = Factory::create(
			new NullIO()
		);

		$package = $composer->getPackage();
		$extra = $package->getExtra();

		$themeInfo = new ThemeInfo();
		$themeInfo->name = $extra['name'] ?? $package->getName();
		$themeInfo->version = $package->getVersion();
		$themeInfo->description = $package->getDescription() ?? '';
		$themeInfo->hasAdmin = isset($extra['screenshots']['admin']);
		$themeInfo->hasUser = isset($extra['screenshots']['user']);
		$themeInfo->screenshots = [
			'user' => $extra['screenshots']['user'] ?? null,
			'admin' => $extra['screenshots']['admin'] ?? null,
		];
		$themeInfo->license = implode(', ', $package->getLicense());
		$themeInfo->path = $path;
		$themeInfo->assets = $extra['assets'] ?? [];

		chdir(__DIR__);

		return $themeInfo;
	}
}