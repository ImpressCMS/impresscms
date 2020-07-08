<?php


namespace ImpressCMS\Core\SetupSteps\Module\Uninstall;

use ImpressCMS\Core\Database\Criteria\CriteriaCompo;
use ImpressCMS\Core\Database\Criteria\CriteriaItem;
use ImpressCMS\Core\Models\Module;
use ImpressCMS\Core\SetupSteps\OutputDecorator;
use ImpressCMS\Core\SetupSteps\SetupStepInterface;
use function icms_getModuleHandler;

class AutotasksSetupStep implements SetupStepInterface
{

	/**
	 * @inheritDoc
	 */
	public function execute(Module $module, OutputDecorator $output, ...$params): bool
	{
		$autotasks = $module->getInfo('autotasks');
		if (!isset($autotasks) || !is_array($autotasks) || (count($autotasks) === 0)) {
			return true;
		}

		$output->success(_MD_AM_AUTOTASKS_DELETE);
		$handler = &icms_getModuleHandler('autotasks', 'system');
		$criteria = new CriteriaCompo();
		$criteria->add(new CriteriaItem('sat_type', 'addon/' . $module->getInfo('dirname')));
		$handler->deleteAll($criteria);

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getPriority(): int
	{
		return 5;
	}
}