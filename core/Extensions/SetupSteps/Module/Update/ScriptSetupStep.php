<?php


namespace ImpressCMS\Core\Extensions\SetupSteps\Module\Update;

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
		list($prev_version, $prev_dbversion) = $params;

		// execute module specific update script if any
		$update_script = $module->getInfo('onUpdate');
		$moduleName = ($module->getInfo('modname') != '') ? trim($module->getInfo('modname')) : $module->dirname;
		if (false !== $update_script && trim($update_script) != '') {
			include_once ICMS_MODULES_PATH . '/' . $module->dirname . '/' . trim($update_script);

			$is_IPF = $module->getInfo('object_items');
			if (!empty($is_IPF)) {
				$icmsDatabaseUpdater = \ImpressCMS\Core\Database\Legacy\DatabaseConnectionFactory::getDatabaseUpdater();
				$icmsDatabaseUpdater->moduleUpgrade($module, true);
				if (!empty($icmsDatabaseUpdater->_messages)) {
					$output->msg(
						implode(PHP_EOL, $icmsDatabaseUpdater->_messages)
					);
				}
			}

			if (function_exists($func = 'xoops_module_uninstall_' . $moduleName)) {
				$this->execFunc($func, $module, $output, $prev_version, $prev_dbversion);
			} elseif (function_exists($func = 'icms_module_uninstall_' . $moduleName)) {
				$this->execFunc($func, $module, $output, $prev_version, $prev_dbversion);
			}
		}

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getPriority(): int
	{
		return PHP_INT_MAX;
	}
}