<?php


namespace ImpressCMS\Core\Extensions\SetupSteps\Module\Uninstall;


use icms;
use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Extensions\SetupSteps\SetupStepInterface;
use ImpressCMS\Core\Models\Module;

class CommentsSetupStep implements SetupStepInterface
{

	/**
	 * @inheritDoc
	 */
	public function execute(Module $module, OutputDecorator $output, ...$params): bool
	{
		if (!$module->hascomments) {
			return true;
		}
		$output->info(_MD_AM_COMMENTS_DELETE);
		$output->incrIndent();
		$comment_handler = icms::handler('icms_data_comment');
		if (!$comment_handler->deleteByModule($module->mid)) {
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