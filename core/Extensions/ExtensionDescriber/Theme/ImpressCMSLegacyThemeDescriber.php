<?php

namespace ImpressCMS\Core\Extensions\ExtensionDescriber\Theme;

use ImpressCMS\Core\Extensions\ExtensionDescriber\DescribedItemInfoInterface;
use ImpressCMS\Core\Extensions\ExtensionDescriber\ExtensionDescriberInterface;
use ImpressCMS\Core\Extensions\ExtensionDescriber\ThemeInfo;

/**
 * Describes theme that was placed as extracted folder
 *
 * @package ImpressCMS\Core\Extensions\ExtensionDescriber\Theme
 */
class ImpressCMSLegacyThemeDescriber implements ExtensionDescriberInterface
{

    /**
     * @inheritDoc
     */
    public function canDescribe(string $path): bool
    {
        return file_exists($path) && is_dir($path);
    }

    /**
     * @inheritDoc
     */
    public function describe(string $path): DescribedItemInfoInterface
    {
    	$isInModule = strpos($path, ICMS_MODULES_PATH) === 0;
    	$adminThemeFile = $isInModule ? 'theme.html' : 'theme_admin.html';

		$themeInfo = new ThemeInfo();
		$themeInfo->name = basename($path);
		$themeInfo->version = '0.0.0';
		$themeInfo->description = '';
		$themeInfo->hasAdmin = $isInModule ? file_exists($path . DIRECTORY_SEPARATOR . 'theme.html') : file_exists($path . DIRECTORY_SEPARATOR . 'theme_admin.html');
		$themeInfo->hasUser = file_exists($path . DIRECTORY_SEPARATOR . 'theme.html') && !$isInModule;
		$themeInfo->screenshots = [
			'user' => null,
			'admin' => null,
		];
		$themeInfo->license = 'GPLv2';
		$themeInfo->path = $path;
		$themeInfo->assets = $extra['assets'] ?? [];

		return $themeInfo;
    }
}