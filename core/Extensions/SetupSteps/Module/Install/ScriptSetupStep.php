<?php


namespace ImpressCMS\Core\Extensions\SetupSteps\Module\Install;


use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Extensions\SetupSteps\SetupStepInterface;
use ImpressCMS\Core\Models\Module;

class ScriptSetupStep implements SetupStepInterface
{

	/**
	 * @inheritDoc
	 */
	public function execute(Module $module, OutputDecorator $output, ...$params): bool
	{
		$install_script = $module->getInfo('onInstall');
		$dirname = $module->dirname;
		$module_name = ($module->getInfo('modname') !== '') ? trim($module->getInfo('modname')) : $dirname;
		if ($install_script === false || empty($install_script = trim($install_script))) {
			return true;
		}
		include_once ICMS_MODULES_PATH . '/' . $dirname . '/' . $install_script;

		$is_IPF = $module->getInfo('object_items');
		if (!empty($is_IPF)) {
			$icmsDatabaseUpdater = \ImpressCMS\Core\Database\Legacy\DatabaseConnectionFactory::getDatabaseUpdater();
			$icmsDatabaseUpdater->moduleUpgrade($module, true);
			$output->msg(
				implode(PHP_EOL, $icmsDatabaseUpdater->_messages)
			);
		}

		if (function_exists($func = 'xoops_module_install_' . $module_name)) {
			$this->execFunc($func, $module, $output);
		} elseif (function_exists($func = 'icms_module_install_' . $module_name)) {
			$this->execFunc($func, $module, $output);
		}

		return true;
	}

	/**
	 * @param string $func Function that should be executed
	 * @param Module $module Module that is installed
	 * @param OutputDecorator $output
	 * @param array $params
	 */
	protected function execFunc($func, Module $module, OutputDecorator $output, ...$params)
	{
		array_unshift($params, $module);

		$last_messages = call_user_func_array($func, $params);
		if (!$last_messages) {
			$output->error(_MD_AM_FAIL_EXEC, $func);
			return;
		}

		if (!empty($module->messages)) {
			$output->msg(
				implode(PHP_EOL, $module->messages)
			);
		}
		$output->success(_MD_AM_FUNCT_EXEC, $func);
		if (is_string($last_messages) && !empty($last_messages)) {
			$output->msg($last_messages);
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