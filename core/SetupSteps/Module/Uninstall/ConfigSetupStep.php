<?php


namespace ImpressCMS\Core\SetupSteps\Module\Uninstall;

use icms;
use icms_db_criteria_Item;
use icms_module_Object;
use ImpressCMS\Core\SetupSteps\OutputDecorator;
use ImpressCMS\Core\SetupSteps\SetupStepInterface;
use function icms_conv_nr2local;

class ConfigSetupStep implements SetupStepInterface
{

	/**
	 * @inheritDoc
	 */
	public function execute(icms_module_Object $module, OutputDecorator $output, ...$params): bool
	{
		if ($module->hasconfig == 0 && $module->hascomments == 0) {
			return true;
		}
		$config_handler = icms::handler('icms_config');
		$configs = $config_handler->getConfigs(new icms_db_criteria_Item('conf_modid', $module->mid));
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