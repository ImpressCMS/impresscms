<?php

namespace ImpressCMS\Core\SetupSteps\Module\Uninstall;

use icms_module_Object;
use ImpressCMS\Core\SetupSteps\OutputDecorator;
use ImpressCMS\Core\SetupSteps\SetupStepInterface;
use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use League\Flysystem\Filesystem;

/**
 * Deletes all module assets
 *
 * @package ImpressCMS\Core\SetupSteps\Module\Uninstall
 */
class CopyAssetsSetupStep implements SetupStepInterface, ContainerAwareInterface
{
	use ContainerAwareTrait;

	/**
	 * @inheritDoc
	 */
	public function execute(icms_module_Object $module, OutputDecorator $output, ...$params): bool
	{
		/**
		 * @var Filesystem $fs
		 */
		$fs = $this->container->get('filesystem.public');
		$output->info(_MD_AM_COPY_ASSETS_DELETING);
		$fs->deleteDir('modules/' . $module->dirname);

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