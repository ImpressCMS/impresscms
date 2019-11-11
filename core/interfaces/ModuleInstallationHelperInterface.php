<?php

namespace ImpressCMS\Core\Interfaces;

use Psr\Log\LoggerInterface;

/**
 * If service has module_installation_helper tag it also needs to implement this interface
 *
 * @package ImpressCMS\Core\Interfaces
 */
interface ModuleInstallationHelperInterface
{

	/**
	 * Execute module install step
	 *
	 * @param \icms_module_Object $module Current module that is installing
	 * @param LoggerInterface $logger Logger where to print messages
	 *
	 * @return bool
	 */
	public function executeModuleInstallStep(\icms_module_Object $module, LoggerInterface $logger): bool;

	/**
	 * Get priority to use to install module
	 *
	 * @return int
	 */
	public function getModuleInstallStepPriority(): int;
}