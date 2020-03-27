<?php


namespace ImpressCMS\Core\SetupSteps\Module\Uninstall;

use icms_db_criteria_Compo;
use icms_db_criteria_Item;
use icms_module_Object;
use ImpressCMS\Core\SetupSteps\OutputDecorator;
use ImpressCMS\Core\SetupSteps\SetupStepInterface;
use function icms_getModuleHandler;

class AutotasksSetupStep implements SetupStepInterface
{

	/**
	 * @inheritDoc
	 */
	public function execute(icms_module_Object $module, OutputDecorator $output, ...$params): bool
	{
		$autotasks = $module->getInfo('autotasks');
		if (!isset($autotasks) || !is_array($autotasks) || (count($autotasks) === 0)) {
			return true;
		}

		$output->success(_MD_AM_AUTOTASKS_DELETE);
		$handler = &icms_getModuleHandler('autotasks', 'system');
		$criteria = new icms_db_criteria_Compo();
		$criteria->add(new icms_db_criteria_Item('sat_type', 'addon/' . $module->getInfo('dirname')));
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