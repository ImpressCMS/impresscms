<?php


namespace ImpressCMS\Core\Extensions\ExtensionDescriber\Module;

use ImpressCMS\Core\Exceptions\UndefinedVariableException;
use ImpressCMS\Core\Extensions\ExtensionDescriber\DescribedItemInfoInterface;
use ImpressCMS\Core\Extensions\ExtensionDescriber\ExtensionDescriberInterface;
use ImpressCMS\Core\Extensions\ExtensionDescriber\ModuleInfo;

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
    public function describe(string $path): DescribedItemInfoInterface
    {
		global $icmsConfig;
		icms_loadLanguageFile(basename($path), 'modinfo');

		include $path . DIRECTORY_SEPARATOR . 'icms_version.php';

		/** @noinspection IssetArgumentExistenceInspection */
		if (!isset($modversion)) {
			throw new UndefinedVariableException('$modversion');
		}

		$modInfo = new ModuleInfo();
		foreach($modversion as $key => $value) {
			$modInfo->$key = $value;
		}

		return $modversion;
    }
}