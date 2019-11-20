<?php


namespace ImpressCMS\Core\ModuleInstallationHelpers;

use icms_module_Object;
use Psr\Log\LoggerInterface;

class NotificationsModuleInstallationHelper implements ModuleInstallationHelperInterface
{

	/**
	 * @inheritDoc
	 */
	public function executeModuleInstallStep(icms_module_Object $module, LoggerInterface $logger): bool
	{
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getModuleInstallStepPriority(): int
	{
		return 100;
	}

	/**
	 * @inheritDoc
	 */
	public function executeModuleUninstallStep(icms_module_Object $module, LoggerInterface $logger): bool
	{
		if (!$module->getVar('hasnotification')) {
			return true;
		}
		$logger->info(_MD_AM_NOTIFICATIONS_DELETE);
		$notification_handler = \icms::handler('icms_data_notification');
		if (!$notification_handler->unsubscribeByModule($module->getVar('mid'))) {
			$logger->error('  ' . _MD_AM_NOTIFICATION_DELETE_FAIL);
		} else {
			$logger->info('  ' . _MD_AM_NOTIFICATION_DELETED);
		}

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