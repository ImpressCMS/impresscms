<?php


namespace ImpressCMS\Core\Extensions\SetupSteps\Module\Uninstall;


use icms;
use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Extensions\SetupSteps\SetupStepInterface;
use ImpressCMS\Core\Models\Module;

class GroupPermissionsSetupStep implements SetupStepInterface
{

	/**
	 * @inheritDoc
	 */
	public function execute(Module $module, OutputDecorator $output, ...$params): bool
	{
		// delete permissions if any
		$output->info(_MD_AM_GROUPPERM_DELETE);
		$output->incrIndent();
		$gperm_handler = icms::handler('icms_member_groupperm');
		if (!$gperm_handler->deleteByModule($module->getVar('mid'))) {
			$output->error(_MD_AM_GROUPPERM_DELETE_FAIL);
		} else {
			$output->success(_MD_AM_GROUPPERM_DELETED);
		}
		$output->decrIndent();

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getPriority(): int
	{
		return 50;
	}
}