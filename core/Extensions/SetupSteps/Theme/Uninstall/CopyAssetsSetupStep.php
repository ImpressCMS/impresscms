<?php

namespace ImpressCMS\Core\Extensions\SetupSteps\Theme\Uninstall;

use icms_module_Object;
use ImpressCMS\Core\Extensions\ExtensionDescriber\ThemeInfo;
use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Extensions\SetupSteps\SetupStepInterface;
use ImpressCMS\Core\Extensions\SetupSteps\Theme\ThemeSetupStepInterface;
use ImpressCMS\Core\Models\Module;
use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use League\Flysystem\Filesystem;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Deletes all module assets
 *
 * @package ImpressCMS\Core\SetupSteps\Theme\Uninstall
 */
class CopyAssetsSetupStep implements ThemeSetupStepInterface, ContainerAwareInterface
{
	use ContainerAwareTrait;

	/**
	 * @inheritDoc
	 */
	public function getPriority(): int
	{
		return 100;
	}

	/**
	 * @inheritDoc
	 */
	public function execute(ThemeInfo $info, OutputDecorator $output, ...$params): bool
	{
		/**
		 * @var TranslatorInterface $trans
		 */
		$trans = $this->container->get('translator');

		/**
		 * @var Filesystem $fs
		 */
		$fs = $this->container->get('filesystem.public');
		$output->info(
			$trans->trans('ADDONS_COPY_ASSETS_DELETING', [], 'addons')
		);
		$fs->deleteDirectory('themes/' . $info->path);

		return true;
	}
}