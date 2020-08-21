<?php


namespace ImpressCMS\Core\SetupSteps\Module\Uninstall;


use icms;
use icms_module_Object;
use ImpressCMS\Core\SetupSteps\OutputDecorator;
use ImpressCMS\Core\SetupSteps\SetupStepInterface;
use function icms_conv_nr2local;

class ViewTemplateSetupStep implements SetupStepInterface
{

	/**
	 * @inheritDoc
	 */
	public function execute(icms_module_Object $module, OutputDecorator $output, ...$params): bool
	{
		$tplfile_handler = icms::handler('icms_view_template_file');
		$templates = $tplfile_handler->find(null, 'module', $module->mid);
		$tcount = count($templates);
		if ($tcount === 0) {
			return true;
		}

		$output->info(_MD_AM_TEMPLATES_DELETE);
		$output->incrIndent();
		for ($i = 0; $i < $tcount; $i++) {
			if (!$tplfile_handler->delete($templates[$i])) {
				$output->error(
					_MD_AM_TEMPLATE_DELETE_FAIL,
					$templates[$i]->tpl_file,
					icms_conv_nr2local($templates[$i]->tpl_id)
				);
			} else {
				$output->success(
					_MD_AM_TEMPLATE_DELETED,
					icms_conv_nr2local($templates[$i]->tpl_file),
					icms_conv_nr2local($templates[$i]->tpl_id)
				);
			}
		}
		$output->decrIndent();

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getPriority(): int
	{
		return 1;
	}
}