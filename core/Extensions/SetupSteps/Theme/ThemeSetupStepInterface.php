<?php

namespace ImpressCMS\Core\Extensions\SetupSteps\Theme;

use ImpressCMS\Core\Extensions\ExtensionDescriber\DescribedItemInfoInterface;
use ImpressCMS\Core\Extensions\ExtensionDescriber\ModuleInfo;
use ImpressCMS\Core\Extensions\ExtensionDescriber\ThemeInfo;
use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Extensions\SetupSteps\SetupStepInterface;

interface ThemeSetupStepInterface extends SetupStepInterface
{

	/**
	 * Execute setup step
	 *
	 * @param ThemeInfo $info Info about current module theme is installed/updated/removed
	 * @param OutputDecorator $output Output where to print messages
	 * @param array $params Extra params
	 *
	 * @return bool
	 */
	public function execute(ThemeInfo $info, OutputDecorator $output, ...$params): bool;

}