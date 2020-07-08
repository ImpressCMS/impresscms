<?php


namespace ImpressCMS\Core\SetupSteps\Module\Install;

use ImpressCMS\Core\Models\Module;
use ImpressCMS\Core\SetupSteps\OutputDecorator;
use ImpressCMS\Core\SetupSteps\SetupStepInterface;
use ImpressCMS\Core\SetupSteps\StepOutput;
use function icms_getModuleHandler;

class AutotasksSetupStep implements SetupStepInterface
{

	/**
	 * @inheritDoc
	 */
	public function execute(Module $module, OutputDecorator $output, ...$params): bool
	{
		$atasks = $module->getInfo('autotasks');
		$output->incrIndent();
		if (isset($atasks) && is_array($atasks) && (count($atasks) > 0)) {
			$handler = icms_getModuleHandler('autotasks', 'system');
			foreach ($atasks as $taskID => $taskData) {
				/**
				 * @var $task \mod_system_Autotasks
				 */
				$task = $handler->create();
				if (isset($taskData['enabled'])) {
					$task->setVar('sat_enabled', $taskData['enabled']);
				}
				if (isset($taskData['repeat'])) {
					$task->setVar('sat_repeat', $taskData['repeat']);
				}
				if (isset($taskData['interval'])) {
					$task->setVar('sat_interval', $taskData['interval']);
				}
				if (isset($taskData['onfinish'])) {
					$task->setVar('sat_onfinish', $taskData['onfinish']);
				}
				$task->setVar('sat_name', $taskData['name']);
				$task->setVar('sat_code', $taskData['code']);
				$task->setVar('sat_type', 'addon/' . $module->getInfo('dirname'));
				$task->setVar('sat_addon_id', (int)$taskID);
				if (!$task->store()) {
					$output->error(_MD_AM_AUTOTASK_FAIL, $taskData['name']);
				} else {
					$output->success(_MD_AM_AUTOTASK_ADDED, $taskData['name']);
				}
			}
			unset($task, $criteria, $items, $taskID);
		}
		$output->resetIndent();

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getPriority(): int
	{
		return 10;
	}
}