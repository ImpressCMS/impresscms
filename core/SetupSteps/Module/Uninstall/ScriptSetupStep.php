<?php


namespace ImpressCMS\Core\SetupSteps\Module\Uninstall;


use icms_db_legacy_Factory;
use icms_module_Object;
use ImpressCMS\Core\SetupSteps\Module\Install\ScriptSetupStep as InstallScriptSetupStep;
use ImpressCMS\Core\SetupSteps\OutputDecorator;

class ScriptSetupStep extends InstallScriptSetupStep
{

	/**
	 * @inheritDoc
	 */
	public function execute(icms_module_Object $module, OutputDecorator $output, ...$params): bool
	{
		$uninstall_script = $module->getInfo('onUninstall');
		$module_name = ($module->getInfo('modname') != '') ? trim($module->getInfo('modname')) : $module->getInfo('dirname');

		if (false === $uninstall_script || empty($uninstall_script = trim($uninstall_script))) {
			return true;
		}

		include_once ICMS_MODULES_PATH . '/' . $module->getInfo('dirname') . '/' . $uninstall_script;

		$is_IPF = $module->getInfo('object_items');
		if (!empty($is_IPF)) {
			$icmsDatabaseUpdater = icms_db_legacy_Factory::getDatabaseUpdater();
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