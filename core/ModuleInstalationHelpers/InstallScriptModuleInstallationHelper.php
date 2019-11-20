<?php

namespace ImpressCMS\Core\ModuleInstallationHelpers;

use icms_db_legacy_Factory;
use icms_module_Object;
use Psr\Log\LoggerInterface;

class InstallScriptModuleInstallationHelper implements ModuleInstallationHelperInterface
{

	/**
	 * @inheritDoc
	 */
	public function executeModuleInstallStep(icms_module_Object $module, LoggerInterface $logger): bool
	{
		$install_script = $module->getInfo('onInstall');
		$dirname = $module->getVar('dirname');
		$module_name = ($module->getInfo('modname') !== '') ? trim($module->getInfo('modname')) : $dirname;
		if ($install_script === false || empty($install_script = trim($install_script))) {
			return true;
		}
		include_once ICMS_MODULES_PATH . '/' . $dirname . '/' . $install_script;

		$is_IPF = $module->getInfo('object_items');
		if (!empty($is_IPF)) {
			$icmsDatabaseUpdater = icms_db_legacy_Factory::getDatabaseUpdater();
			$icmsDatabaseUpdater->moduleUpgrade($module, true);
			foreach ($icmsDatabaseUpdater->_messages as $message) {
				$logger->notice($message);
			}
		}

		if (function_exists($func = 'xoops_module_install_' . $module_name)) {
			$this->execFunc($func, $module, $logger);
		} elseif (function_exists($func = 'icms_module_install_' . $module_name)) {
			$this->execFunc($func, $module, $logger);
		}

		return true;
	}

	/**
	 * @param string $func Function that should be executed
	 * @param icms_module_Object $module Module that is installed
	 * @param LoggerInterface $logger Logger where to write messages
	 */
	protected function execFunc($func, icms_module_Object $module, LoggerInterface $logger)
	{
		$last_messages = $func($module);
		if (!$last_messages) {
			$logger->error(
				sprintf(_MD_AM_FAIL_EXEC, $func)
			);
			return;
		}

		$logger->notice($module->messages);
		$logger->info(
			sprintf(_MD_AM_FUNCT_EXEC, $func)
		);
		if (is_string($last_messages)) {
			$logger->info($last_messages);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function getModuleInstallStepPriority(): int
	{
		return PHP_INT_MAX;
	}

	/**
	 * @inheritDoc
	 */
	public function executeModuleUninstallStep(icms_module_Object $module, LoggerInterface $logger): bool
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
			foreach ($icmsDatabaseUpdater->_messages as $message) {
				$logger->notice($message);
			}
		}

		if (function_exists($func = 'xoops_module_uninstall_' . $module_name)) {
			$this->execFunc($func, $module, $logger);
		} elseif (function_exists($func = 'icms_module_uninstall_' . $module_name)) {
			$this->execFunc($func, $module, $logger);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function getModuleUninstallStepPriority(): int
	{
		return PHP_INT_MAX;
	}
}