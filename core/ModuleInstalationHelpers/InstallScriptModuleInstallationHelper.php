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
		$ModName = ($module->getInfo('modname') !== '') ? trim($module->getInfo('modname')) : $dirname;
		if (false !== $install_script && trim($install_script) !== '') {
			include_once ICMS_MODULES_PATH . '/' . $dirname . '/' . trim($install_script);

			$is_IPF = $module->getInfo('object_items');
			if (!empty($is_IPF)) {
				$icmsDatabaseUpdater = icms_db_legacy_Factory::getDatabaseUpdater();
				$icmsDatabaseUpdater->moduleUpgrade($module, true);
				foreach ($icmsDatabaseUpdater->_messages as $message) {
					$logger->notice($message);
				}
			}

			if (function_exists($func = 'xoops_module_install_' . $ModName)) {
				$this->execOnInstall($func, $module, $logger);
			} elseif (function_exists($func = 'icms_module_install_' . $ModName)) {
				$this->execOnInstall($func, $module, $logger);
			}
		}

		return true;
	}

	/**
	 * @param string $func Function that should be executed
	 * @param icms_module_Object $module Module that is installed
	 * @param LoggerInterface $logger Logger where to write messages
	 */
	protected function execOnInstall($func, icms_module_Object $module, LoggerInterface $logger)
	{
		if (!($lastmsg = $func($module))) {
			$logger->error(
				sprintf(_MD_AM_FAIL_EXEC, $func)
			);
		} else {
			$logger->notice($module->messages);
			$logger->info(
				sprintf(_MD_AM_FUNCT_EXEC, $func)
			);
			if (is_string($lastmsg)) {
				$logger->info($lastmsg);
			}
		}
	}

	/**
	 * @inheritDoc
	 */
	public function getModuleInstallStepPriority(): int
	{
		return PHP_INT_MAX;
	}
}