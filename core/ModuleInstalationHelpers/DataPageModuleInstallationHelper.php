<?php

namespace ImpressCMS\Core\ModuleInstallationHelpers;

use icms_module_Object;
use Psr\Log\LoggerInterface;

class DataPageModuleInstallationHelper implements ModuleInstallationHelperInterface
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
		/**
		 * @var \icms_data_page_Handler $page_handler
		 */
		$page_handler = \icms::handler('icms_data_page');
		$criteria = new \icms_db_criteria_Compo(
			new \icms_db_criteria_Item('page_moduleid', $module->getVar('mid'))
		);
		$pages = (int)$page_handler->getCount($criteria);
		if ($pages === 0) {
			return true;
		}

		$pages = $page_handler->getObjects($criteria);
		$logger->info(_MD_AM_SYMLINKS_DELETE);
		foreach ($pages as $page) {
			if (!$page_handler->delete($page)) {
				$logger->error(
					sprintf('  ' . _MD_AM_SYMLINK_DELETE_FAIL, $page->getVar('page_title'), $page->getVar('page_id'))
				);
			} else {
				$logger->info(
					sprintf('  ' . _MD_AM_SYMLINK_DELETED, $page->getVar('page_title'), $page->getVar('page_id'))
				);
			}
		}

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getModuleUninstallStepPriority(): int
	{
		return -1;
	}
}