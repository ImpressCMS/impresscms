<?php

namespace ImpressCMS\Core\SetupSteps;

use icms_module_Object;

interface SetupStepInterface
{

	/**
	 * Execute setup step
	 *
	 * @param icms_module_Object $module Current module that is installing
	 * @param OutputDecorator $output Output where to print messages
	 * @param array $params Extra params
	 *
	 * @return bool
	 */
	public function execute(icms_module_Object $module, OutputDecorator $output, ...$params): bool;

	/**
	 * Get priority to use this step
	 *
	 * @return int
	 */
	public function getPriority(): int;

}