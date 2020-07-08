<?php

namespace ImpressCMS\Core\SetupSteps\Module\Uninstall;

use icms;
use ImpressCMS\Core\Database\Criteria\CriteriaCompo;
use ImpressCMS\Core\Database\Criteria\CriteriaItem;
use ImpressCMS\Core\Models\Module;
use ImpressCMS\Core\Models\PageHandler;
use ImpressCMS\Core\SetupSteps\OutputDecorator;
use ImpressCMS\Core\SetupSteps\SetupStepInterface;

class DataPageSetupStep implements SetupStepInterface
{

	/**
	 * @inheritDoc
	 */
	public function execute(Module $module, OutputDecorator $output, ...$params): bool
	{
		/**
		 * @var PageHandler $page_handler
		 */
		$page_handler = icms::handler('icms_data_page');
		$criteria = new CriteriaCompo(
			new CriteriaItem('page_moduleid', $module->getVar('mid'))
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
				$output->success(_MD_AM_SYMLINK_DELETED, $page->getVar('page_title'), $page->getVar('page_id'));
			} else {
				$output->error(_MD_AM_SYMLINK_DELETE_FAIL, $page->getVar('page_title'), $page->getVar('page_id'));
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