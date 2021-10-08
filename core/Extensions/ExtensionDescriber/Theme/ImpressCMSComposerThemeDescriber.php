<?php

namespace ImpressCMS\Core\Extensions\ExtensionDescriber\Theme;

use Composer\Factory;
use Composer\IO\NullIO;
use ImpressCMS\Core\Extensions\ExtensionDescriber\ExtensionDescriberInterface;

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
	public function describe(string $path): array
	{
		chdir($path);
		putenv('COMPOSER_HOME=' . ICMS_STORAGE_PATH . '/composer');
		$composer = Factory::create(
			new NullIO()
		);

		$package = $composer->getPackage();
		$extra = $package->getExtra();

		$themeInfo = [
			'name' => $extra['name'] ?? $package->getName(),
			'version' => $package->getVersion(),
			'description' => $package->getDescription(),
			'hasAdmin' => isset($extra['screenshots']['admin']),
			'hasUser' => isset($extra['screenshots']['user']),
			'screenshots' => [
				'user' => $extra['screenshots']['user'] ?? null,
				'admin' => $extra['screenshots']['admin'] ?? null,
			],
			'license' => implode(', ', $package->getLicense()),
			'path' => $path
		];

		chdir(__DIR__);

		return array_filter($themeInfo);
	}
}