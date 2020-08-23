<?php


namespace ImpressCMS\Core\Extensions\SetupSteps\Module\Uninstall;


use icms;
use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Extensions\SetupSteps\SetupStepInterface;
use ImpressCMS\Core\Models\Module;
use function icms_buildCriteria;

class UrlLinksSetupStep implements SetupStepInterface
{

	/**
	 * @inheritDoc
	 */
	public function execute(Module $module, OutputDecorator $output, ...$params): bool
	{
		// delete urllinks
		try {
			$urllink_handler = icms::handler('icms_data_urllink');
			$urllink_handler->deleteAll(icms_buildCriteria(array("mid" => $module->mid)));
		} catch (\Exception $exception) {

		}

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