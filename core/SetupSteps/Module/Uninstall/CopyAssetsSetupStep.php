<?php

namespace ImpressCMS\Core\SetupSteps\Module\Uninstall;

use icms_module_Object;
use ImpressCMS\Core\SetupSteps\OutputDecorator;
use ImpressCMS\Core\SetupSteps\SetupStepInterface;
use League\Flysystem\MountManager;

/**
 * Deletes all module assets
 *
 * @package ImpressCMS\Core\SetupSteps\Module\Uninstall
 */
class CopyAssetsSetupStep implements SetupStepInterface
{

	/**
	 * @inheritDoc
	 */
	public function execute(icms_module_Object $module, OutputDecorator $output, ...$params): bool
	{
		/**
		 * @var MountManager $mm
		 */
		$mm = \icms::getInstance()->get('filesystem');
		$output->info(_MD_AM_COPY_ASSETS_DELETING);
		$mm->deleteDir('public://modules/' . $module->dirname);

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getPriority(): int
	{
		return 100;
	}
}