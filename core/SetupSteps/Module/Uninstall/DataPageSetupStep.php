<?php

namespace ImpressCMS\Core\SetupSteps\Module\Uninstall;

use icms;
use icms_data_page_Handler;
use icms_db_criteria_Compo;
use icms_db_criteria_Item;
use icms_module_Object;
use ImpressCMS\Core\SetupSteps\OutputDecorator;
use ImpressCMS\Core\SetupSteps\SetupStepInterface;

class DataPageSetupStep implements SetupStepInterface
{

	/**
	 * @inheritDoc
	 */
	public function execute(icms_module_Object $module, OutputDecorator $output, ...$params): bool
	{
		/**
		 * @var icms_data_page_Handler $page_handler
		 */
		$page_handler = icms::handler('icms_data_page');
		$criteria = new icms_db_criteria_Compo(
			new icms_db_criteria_Item('page_moduleid', $module->mid)
		);
		$pages = (int)$page_handler->getCount($criteria);
		if ($pages === 0) {
			return true;
		}

		$pages = $page_handler->getObjects($criteria);
		$output->info(_MD_AM_SYMLINKS_DELETE);
		$output->incrIndent();
		foreach ($pages as $page) {
			if ($page_handler->delete($page)) {
				$output->success(_MD_AM_SYMLINK_DELETED, $page->page_title, $page->page_id);
			} else {
				$output->error(_MD_AM_SYMLINK_DELETE_FAIL, $page->page_title, $page->page_id);
			}
		}
		$output->resetIndent();

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getPriority(): int
	{
		return -1;
	}
}