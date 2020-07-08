<?php


namespace ImpressCMS\Core\SetupSteps\Module\Install;


use ImpressCMS\Core\Models\Module;
use ImpressCMS\Core\SetupSteps\OutputDecorator;
use ImpressCMS\Core\SetupSteps\SetupStepInterface;
use League\Flysystem\MountManager;

/**
 * Copies module assets to public path
 *
 * @package ImpressCMS\Core\SetupSteps\Module\Install
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
		$output->info(_MD_AM_COPY_ASSETS_INFO);
		$output->incrIndent();
		$output->msg(_MD_AM_COPY_ASSETS_DELETE_OLD);
		$mm->deleteDir('public://modules/' . $module->dirname);
		$mm->createDir('public://modules/' . $module->dirname);
		foreach ($mm->listContents('modules://' . $module->dirname, true) as $fileSystemItem) {
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
				$mm->copy('modules://' . $fileSystemItem['path'], 'public://modules/' . $fileSystemItem['path']);
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