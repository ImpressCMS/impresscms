<?php


namespace ImpressCMS\Core\SetupSteps\Module\Uninstall;


use icms;
use ImpressCMS\Core\Models\Module;
use ImpressCMS\Core\SetupSteps\OutputDecorator;
use ImpressCMS\Core\SetupSteps\SetupStepInterface;

class CommentsSetupStep implements SetupStepInterface
{

	/**
	 * @inheritDoc
	 */
	public function execute(Module $module, OutputDecorator $output, ...$params): bool
	{
		if (!$module->getVar('hascomments')) {
			return true;
		}
		$output->info(_MD_AM_COMMENTS_DELETE);
		$output->incrIndent();
		$comment_handler = icms::handler('icms_data_comment');
		if (!$comment_handler->deleteByModule($module->getVar('mid'))) {
			$output->error(_MD_AM_COMMENT_DELETE_FAIL);
		} else {
			$output->success(_MD_AM_COMMENT_DELETED);
		}
		$output->decrIndent();

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getPriority(): int
	{
		return 50;
	}
}