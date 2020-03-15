<?php


namespace ImpressCMS\Core\SetupSteps\Module\Update;

use icms_db_criteria_Compo;
use icms_db_criteria_Item;
use icms_module_Object;
use ImpressCMS\Core\SetupSteps\OutputDecorator;
use ImpressCMS\Core\SetupSteps\SetupStepInterface;
use mod_system_Autotasks;
use function icms_getModuleHandler;

class AutotasksSetupStep implements SetupStepInterface
{

	/**
	 * @inheritDoc
	 */
	public function execute(icms_module_Object $module, OutputDecorator $output, ...$params): bool
	{
		// add module specific tasks to system autotasks list
		$atasks = $module->getInfo('autotasks');
		$atasks_handler = &icms_getModuleHandler('autotasks', 'system');
		if (isset($atasks) && is_array($atasks) && (count($atasks) > 0)) {
			$output->info(_MD_AM_AUTOTASK_UPDATE);
			$output->incrIndent();
			$criteria = new icms_db_criteria_Compo();
			$criteria->add(new icms_db_criteria_Item('sat_type', 'addon/' . $module->getInfo('dirname')));
			$items_atasks = $atasks_handler->getObjects($criteria, false);
			foreach ($items_atasks as $task) {
				$taskID = (int)$task->getVar('sat_addon_id');
				if (!isset($atasks[$taskID])) {
					$atasks[$taskID] = [];
				}
				$atasks[$taskID]['enabled'] = $task->getVar('sat_enabled');
				$atasks[$taskID]['repeat'] = $task->getVar('sat_repeat');
				$atasks[$taskID]['interval'] = $task->getVar('sat_interval');
				$atasks[$taskID]['name'] = $task->getVar('sat_name');
			}
			$atasks_handler->deleteAll($criteria);
			if (is_array($atasks)) {
				foreach ($atasks as $taskID => $taskData) {
					if (!isset($taskData['code']) || trim($taskData['code']) == '') {
						continue;
					}
					/**
					 * @var mod_system_Autotasks $task
					 */
					$task = &$atasks_handler->create();
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
						$output->error(_MD_AM_AUTOTASK_FAIL . ' ' . $taskData['name']);
					} else {
						$output->success(_MD_AM_AUTOTASK_ADDED . ' ' . $taskData['name']);
					}
				}
			}
			unset($atasks, $atasks_handler, $task, $taskData, $criteria, $items, $taskID);
		}
		$output->resetIndent();

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getPriority(): int
	{
		return 20;
	}
}