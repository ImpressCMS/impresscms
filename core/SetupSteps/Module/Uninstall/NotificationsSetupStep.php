<?php


namespace ImpressCMS\Core\SetupSteps\Module\Uninstall;


use icms;
use icms_module_Object;
use ImpressCMS\Core\SetupSteps\OutputDecorator;
use ImpressCMS\Core\SetupSteps\SetupStepInterface;

class NotificationsSetupStep implements SetupStepInterface
{

	/**
	 * @inheritDoc
	 */
	public function execute(icms_module_Object $module, OutputDecorator $output, ...$params): bool
	{
		if (!$module->hasnotification) {
			return true;
		}
		$output->info(_MD_AM_NOTIFICATIONS_DELETE);
		$output->incrIndent();
		$notification_handler = icms::handler('icms_data_notification');
		if (!$notification_handler->unsubscribeByModule($module->mid)) {
			$output->error(_MD_AM_NOTIFICATION_DELETE_FAIL);
		} else {
			$output->success(_MD_AM_NOTIFICATION_DELETED);
		}
		$output->resetIndent();

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getPriority(): int
	{
		return 5;
	}
}