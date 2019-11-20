<?php


namespace ImpressCMS\Core\ModuleInstallationHelpers;

use icms_module_Object;
use Psr\Log\LoggerInterface;

class CommentsModuleInstallationHelper implements ModuleInstallationHelperInterface
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
		if (!$module->getVar('hascomments')) {
			return true;
		}
		$logger->info(_MD_AM_COMMENTS_DELETE);
		$comment_handler = \icms::handler('icms_data_comment');
		if (!$comment_handler->deleteByModule($module->getVar('mid'))) {
			$logger->error('  ' . _MD_AM_COMMENT_DELETE_FAIL);
		} else {
			$logger->info('  ' . _MD_AM_COMMENT_DELETED);
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