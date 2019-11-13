<?php

namespace ImpressCMS\Core\ModuleInstallationHelpers;

use icms_module_Object;
use Psr\Log\LoggerInterface;

/**
 * If service has module_installation_helper tag it also needs to implement this interface
 *
 * @package ImpressCMS\Core\ModuleInstallationHelpers
 */
interface ModuleInstallationHelperInterface
{

	/**
	 * Execute module install step
	 *
	 * @param icms_module_Object $module Current module that is installing
	 * @param LoggerInterface $logger Logger where to print messages
	 *
	 * @return bool
	 */
	public function executeModuleInstallStep(icms_module_Object $module, LoggerInterface $logger): bool;

	/**
	 * Get priority to use to install module
	 *
	 * @return int
	 */
	public function getModuleInstallStepPriority(): int;
}