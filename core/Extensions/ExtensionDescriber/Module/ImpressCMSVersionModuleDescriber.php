<?php


namespace ImpressCMS\Core\Extensions\ExtensionDescriber\Module;

use ImpressCMS\Core\Exceptions\UndefinedVariableException;
use ImpressCMS\Core\Extensions\ExtensionDescriber\ExtensionDescriberInterface;

/**
 * Describes modules that has icms_version.php file
 *
 * @package ImpressCMS\Core\Extensions\ExtensionDescriber\Module
 */
class ImpressCMSVersionModuleDescriber implements ExtensionDescriberInterface
{

    /**
     * @inheritDoc
     */
    public function canDescribe(string $path): bool
    {
		return file_exists($path . DIRECTORY_SEPARATOR . 'icms_version.php');
    }

    /**
     * @inheritDoc
     */
    public function describe(string $path): array
    {
		global $icmsConfig;
		icms_loadLanguageFile(basename($path), 'modinfo');

		include $path . DIRECTORY_SEPARATOR . 'icms_version.php';

		if (!isset($modversion)) {
			throw new UndefinedVariableException('$modversion');
		}

		return $modversion;
    }
}