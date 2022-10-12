<?php

namespace ImpressCMS\Core\Extensions\SetupSteps\Theme\Install;

use Exception;
use Generator;
use ImpressCMS\Core\Extensions\ExtensionDescriber\ThemeInfo;
use ImpressCMS\Core\Extensions\SetupSteps\AddonAssetsTrait;
use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Extensions\SetupSteps\SetupStepInterface;
use ImpressCMS\Core\Extensions\SetupSteps\Theme\ThemeSetupStepInterface;
use ImpressCMS\Core\Models\Module;
use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use League\Flysystem\FileAttributes;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\Filesystem;
use League\Flysystem\StorageAttributes;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Copies theme assets to public path
 *
 * @package ImpressCMS\Core\SetupSteps\Theme\Install
 */
class CopyAssetsSetupStep implements ThemeSetupStepInterface, ContainerAwareInterface
{
	use ContainerAwareTrait, AddonAssetsTrait;

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

		$output->info(
			$trans->trans('ADDONS_COPY_ASSETS_INFO', [], 'addons')
		);
		$output->incrIndent();
		$output->msg(
			$trans->trans('ADDONS_COPY_ASSETS_DELETE_OLD', [], 'addons')
		);
		$this->recreateAssetsPublicFolderPath($info->path, 'themes');

		$assetsCopier = $this->copyAllAssets(
			$info->assets,
			$info->path,
			'themes'
		);
		foreach ($assetsCopier as $assetPath) {
			$output->msg(
				$trans->trans('ADDONS_COPY_ASSETS_COPYING', ['%file%' => $assetPath], 'addons')
			);
		}

		$output->decrIndent();

		return true;
	}
}
