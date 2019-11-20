<?php

namespace ImpressCMS\Core\ModuleInstallationHelpers;

use icms_module_Object;
use Psr\Log\LoggerInterface;

class AutotaskModuleInstalationHelper implements ModuleInstallationHelperInterface
{

	/**
	 * @inheritDoc
	 */
	public function executeModuleInstallStep(icms_module_Object $module, LoggerInterface $logger): bool
	{
		$atasks = $module->getInfo('autotasks');
		if (isset($atasks) && is_array($atasks) && (count($atasks) > 0)) {
			$handler = icms_getModuleHandler('autotasks', 'system');
			foreach ($atasks as $taskID => $taskData) {
				/**
				 * @var $task mod_system_Autotasks
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
					$logger->error(
						sprintf('  ' . _MD_AM_AUTOTASK_FAIL, $taskData['name'])
					);
				} else {
					$logger->info(
						sprintf('  ' . _MD_AM_AUTOTASK_ADDED, $taskData['name'])
					);
				}
			}
			unset($task, $criteria, $items, $taskID);
		}

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getModuleInstallStepPriority(): int
	{
		return 10;
	}

	/**
	 * @inheritDoc
	 */
	public function executeModuleUninstallStep(icms_module_Object $module, LoggerInterface $logger): bool
	{
		$atasks = $module->getInfo('autotasks');
		if (!isset($atasks) || !is_array($atasks) || (count($atasks) === 0)) {
			return true;
		}

		$logger->info(_MD_AM_AUTOTASKS_DELETE);
		$atasks_handler = &icms_getModuleHandler('autotasks', 'system');
		$criteria = new \icms_db_criteria_Compo();
		$criteria->add(new \icms_db_criteria_Item('sat_type', 'addon/' . $module->getInfo('dirname')));
		$atasks_handler->deleteAll($criteria);

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getModuleUninstallStepPriority(): int
	{
		return 5;
	}
}