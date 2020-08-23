<?php


namespace ImpressCMS\Core\Extensions\SetupSteps\Module\Uninstall;


use icms;
use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Extensions\SetupSteps\SetupStepInterface;
use ImpressCMS\Core\Models\Module;
use function icms_conv_nr2local;

class BlockSetupStep implements SetupStepInterface
{

	/**
	 * @inheritDoc
	 */
	public function execute(Module $module, OutputDecorator $output, ...$params): bool
	{
		$icms_block_handler = icms::handler('icms_view_block');
		$block_arr = $icms_block_handler->getByModule($module->getVar('mid'));
		if (!is_array($block_arr)) {
			return true;
		}
		$bcount = count($block_arr);
		$output->info(_MD_AM_BLOCKS_DELETE);
		$output->incrIndent();
		$tplfile_handler = icms::handler('icms_view_template_file');
		for ($i = 0; $i < $bcount; $i++) {
			if (!$icms_block_handler->delete($block_arr[$i])) {
				$output->error(
					_MD_AM_BLOCK_DELETE_FAIL,
					$block_arr[$i]->getVar('name'),
					icms_conv_nr2local($block_arr[$i]->getVar('bid'))
				);
			} else {
				$output->success(
					_MD_AM_BLOCK_DELETED,
					$block_arr[$i]->getVar('name'),
					icms_conv_nr2local($block_arr[$i]->getVar('bid'))
				);
			}
			if ($block_arr[$i]->getVar('template') != '') {
				$templates = $tplfile_handler->find(null, 'block', $block_arr[$i]->getVar('bid'));
				$btcount = count($templates);
				if ($btcount > 0) {
					for ($j = 0; $j < $btcount; $j++) {
						if (!$tplfile_handler->delete($templates[$j])) {
							$output->error(
								_MD_AM_BLOCK_TMPLT_DELETE_FAILED,
								$templates[$j]->getVar('tpl_file'),
								icms_conv_nr2local($templates[$j]->getVar('tpl_id'))
							);
						} else {
							$output->success(
								_MD_AM_BLOCK_TMPLT_DELETED,
								$templates[$j]->getVar('tpl_file'),
								icms_conv_nr2local($templates[$j]->getVar('tpl_id'))
							);
						}
					}
				}
				unset($templates);
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
		return 2;
	}
}