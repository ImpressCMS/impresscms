<?php

namespace ImpressCMS\Core\Extensions\SetupSteps\Module;

use ImpressCMS\Core\Extensions\ExtensionDescriber\DescribedItemInfoInterface;
use ImpressCMS\Core\Extensions\ExtensionDescriber\ModuleInfo;
use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Extensions\SetupSteps\SetupStepInterface;
use ImpressCMS\Core\Models\Module;

interface ModuleSetupStepInterface extends SetupStepInterface
{

	/**
	 * Execute setup step
	 *
	 * @param Module $module Module to update/install/uninstall
	 * @param OutputDecorator $output Output where to print messages
	 * @param array $params Extra params
	 *
	 * @return bool
	 */
	public function execute(Module $module, OutputDecorator $output, ...$params): bool;

}