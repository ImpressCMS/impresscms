<?php

namespace ImpressCMS\Core\Extensions\ExtensionDescriber\Theme;

use ImpressCMS\Core\Extensions\ExtensionDescriber\ExtensionDescriberInterface;

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
    public function describe(string $path): array
    {
    	$isInModule = strpos($path, ICMS_MODULES_PATH) === 0;
    	$adminThemeFile = $isInModule ? 'theme.html' : 'theme_admin.html';

		$themeInfo = [
			'name' => basename($path),
			'version' => '0.0.0',
			'description' => '',
			'hasUser' => file_exists($path . DIRECTORY_SEPARATOR . 'theme.html') && !$isInModule,
			'hasAdmin' => $isInModule ? file_exists($path . DIRECTORY_SEPARATOR . 'theme.html') : file_exists($path . DIRECTORY_SEPARATOR . 'theme_admin.html'),
			'screenshots' => [
				'user' => null,
				'admin' => null,
			],
			'license' => 'GPLv2',
			'path' => $path
		];

		return array_filter($themeInfo);
    }
}