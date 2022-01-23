<?php


namespace ImpressCMS\Core\Extensions\SetupSteps\Module\Update;

use ImpressCMS\Core\Database\Criteria\CriteriaCompo;
use ImpressCMS\Core\Database\Criteria\CriteriaItem;
use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Extensions\SetupSteps\SetupStepInterface;
use ImpressCMS\Core\Models\Module;
use mod_system_Autotasks;
use function icms_getModuleHandler;

class AutotasksSetupStep implements SetupStepInterface
{

	/**
	 * @inheritDoc
	 */
	public function execute(Module $module, OutputDecorator $output, ...$params): bool
	{
		// add module specific tasks to system autotasks list
		$atasks = $module->getInfo('autotasks');
		$atasks_handler = &icms_getModuleHandler('autotasks', 'system');
		if (isset($atasks) && is_array($atasks) && (count($atasks) > 0)) {
			$output->info(_MD_AM_AUTOTASK_UPDATE);
			$output->incrIndent();
			$criteria = new CriteriaCompo();
			$criteria->add(new CriteriaItem('sat_type', 'addon/'.$module->getInfo('dirname')));
			$items_atasks = $atasks_handler->getObjects($criteria, false);
			foreach ($items_atasks as $task) {
				$taskID = (int) $task->sat_addon_id;
				if (!isset($atasks[$taskID])) {
					$atasks[$taskID] = [];
				}
				$atasks[$taskID]['enabled'] = $task->sat_enabled;
				$atasks[$taskID]['repeat'] = $task->sat_repeat;
				$atasks[$taskID]['interval'] = $task->sat_interval;
				$atasks[$taskID]['name'] = $task->sat_name;
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
						$task->sat_enabled = $taskData['enabled'];
					}
					if (isset($taskData['repeat'])) {
						$task->sat_repeat = $taskData['repeat'];
					}
					if (isset($taskData['interval'])) {
						$task->sat_interval = $taskData['interval'];
					}
					if (isset($taskData['onfinish'])) {
						$task->sat_onfinish = $taskData['onfinish'];
					}
					$task->sat_name = $taskData['name'];
					$task->sat_code = $taskData['code'];
					$task->sat_type = 'addon/'.$module->getInfo('dirname');
					$task->sat_addon_id = $taskID;
					if (!$task->store()) {
						$output->error(_MD_AM_AUTOTASK_FAIL, $taskData['name']);
					} else {
						$output->success(_MD_AM_AUTOTASK_ADDED, $taskData['name']);
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