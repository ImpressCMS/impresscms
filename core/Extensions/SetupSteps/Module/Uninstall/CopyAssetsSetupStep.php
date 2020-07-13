<?php

namespace ImpressCMS\Core\Extensions\SetupSteps\Module\Uninstall;

use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Extensions\SetupSteps\SetupStepInterface;
use ImpressCMS\Core\Models\Module;
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
	public function execute(Module $module, OutputDecorator $output, ...$params): bool
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