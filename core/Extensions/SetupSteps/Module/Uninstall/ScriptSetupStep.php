<?php

namespace ImpressCMS\Core\Extensions\SetupSteps\Module\Uninstall;

use ImpressCMS\Core\Database\Legacy\DatabaseConnectionFactory;
use ImpressCMS\Core\Extensions\SetupSteps\Module\Install\ScriptSetupStep as InstallScriptSetupStep;
use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Models\Module;

class ScriptSetupStep extends InstallScriptSetupStep
{

	/**
	 * @inheritDoc
	 */
	public function execute(Module $module, OutputDecorator $output, ...$params): bool
	{
		$uninstall_script = $module->getInfo('onUninstall');
		$module_name = ($module->getInfo('modname') != '') ? trim($module->getInfo('modname')) : $module->getInfo('dirname');

		if (false === $uninstall_script || empty($uninstall_script = trim($uninstall_script))) {
			return true;
		}

		include_once ICMS_MODULES_PATH . '/' . $module->getInfo('dirname') . '/' . $uninstall_script;

		$is_IPF = $module->getInfo('object_items');
		if (!empty($is_IPF)) {
			$icmsDatabaseUpdater = DatabaseConnectionFactory::getDatabaseUpdater();
			$icmsDatabaseUpdater->moduleUpgrade($module, true);
			if (!empty($icmsDatabaseUpdater->_messages)) {
				$output->msg(
					implode(PHP_EOL, $icmsDatabaseUpdater->_messages)
				);
			}
		}

		if (function_exists($func = 'xoops_module_uninstall_' . $module_name)) {
			$this->execFunc($func, $module, $output);
		} elseif (function_exists($func = 'icms_module_uninstall_' . $module_name)) {
			$this->execFunc($func, $module, $output);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function getPriority(): int
	{
		return PHP_INT_MAX;
	}
}