<?php


namespace ImpressCMS\Core\Extensions\SetupSteps\Module\Install;


use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Extensions\SetupSteps\SetupStepInterface;
use ImpressCMS\Core\Models\Module;
use icms_module_Object;
use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use League\Flysystem\Filesystem;

/**
 * Copies module assets to public path
 *
 * @package ImpressCMS\Core\SetupSteps\Module\Install
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
		 * @var Filesystem $mm
		 */
		$mm = $this->container->get('filesystem.public');
		$output->info(_MD_AM_COPY_ASSETS_INFO);
		$output->incrIndent();
		$output->msg(_MD_AM_COPY_ASSETS_DELETE_OLD);
		$mm->deleteDir('modules/' . $module->dirname);
		$mm->createDir('modules/' . $module->dirname);

		/**
		 * @var Filesystem $mf
		 */
		$mf = $this->container->get('filesystem.modules');
		foreach ($mf->listContents( $module->dirname, true) as $fileSystemItem) {
			if ($fileSystemItem['type'] !== 'file') {
				continue;
			}
			if ($fileSystemItem['extension'] === 'php') {
				continue;
			}
			if (
				(($fileSystemItem['extension'] === 'css') && ($fileSystemItem['dirname'] === $module->dirname)) ||
				(strpos($fileSystemItem['path'], $module->dirname . '/images/') === 0) ||
				(strpos($fileSystemItem['path'], $module->dirname . '/css/') === 0) ||
				(strpos($fileSystemItem['path'], $module->dirname . '/js/') === 0) ||
				(strpos($fileSystemItem['path'], $module->dirname . '/themes/') === 0)
			) {
				$output->msg(_MD_AM_COPY_ASSETS_COPYING, $fileSystemItem['path']);
				$mm->writeStream(
					'modules/' . $fileSystemItem['path'],
					$mf->readStream($fileSystemItem['path'])
				);
			}
		}
		$output->decrIndent();

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