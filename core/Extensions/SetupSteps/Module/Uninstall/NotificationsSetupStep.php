<?php


namespace ImpressCMS\Core\Extensions\SetupSteps\Module\Uninstall;


use icms;
use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Extensions\SetupSteps\SetupStepInterface;
use ImpressCMS\Core\Models\Module;

class NotificationsSetupStep implements SetupStepInterface
{

	/**
	 * @inheritDoc
	 */
	public function execute(Module $module, OutputDecorator $output, ...$params): bool
	{
		if (!$module->getVar('hasnotification')) {
			return true;
		}
		$output->info(_MD_AM_NOTIFICATIONS_DELETE);
		$output->incrIndent();
		$notification_handler = icms::handler('icms_data_notification');
		if (!$notification_handler->unsubscribeByModule($module->getVar('mid'))) {
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