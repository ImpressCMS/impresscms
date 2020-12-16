<?php


namespace ImpressCMS\Core\Extensions\SetupSteps\Module\Uninstall;

use icms;
use ImpressCMS\Core\Database\Criteria\CriteriaItem;
use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Extensions\SetupSteps\SetupStepInterface;
use ImpressCMS\Core\Models\Module;
use function icms_conv_nr2local;

class ConfigSetupStep implements SetupStepInterface
{

	/**
	 * @inheritDoc
	 */
	public function execute(Module $module, OutputDecorator $output, ...$params): bool
	{
		if ($module->hasconfig == 0 && $module->hascomments == 0) {
			return true;
		}
		$config_handler = icms::handler('icms_config');
		$configs = $config_handler->getConfigs(new CriteriaItem('conf_modid', $module->getVar('mid')));
		$confcount = count($configs);
		if ($confcount > 0) {
			return true;
		}
		$output->info(_MD_AM_CONFIGOPTIONS_DELETE);
		$output->incrIndent();
		for ($i = 0; $i < $confcount; $i++) {
			if (!$config_handler->deleteConfig($configs[$i])) {
				$output->error(_MD_AM_CONFIGOPTION_DELETE_FAIL, icms_conv_nr2local($configs[$i]->conf_id));
			} else {
				$output->success(_MD_AM_CONFIGOPTION_DELETED, icms_conv_nr2local($configs[$i]->conf_id));
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
		return 5;
	}
}