<?php

namespace ImpressCMS\Core\Extensions\SetupSteps\Module\Uninstall;

use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Extensions\SetupSteps\SetupStepInterface;
use ImpressCMS\Core\Models\Module;
use icms_module_Object;
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
	public function execute(Module $module, OutputDecorator $output, ...$params): bool
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