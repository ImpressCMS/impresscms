<?php

namespace ImpressCMS\Core\Extensions\SetupSteps;

use ImpressCMS\Core\Models\Module;

/**
 * Interface that defines how setup step works
 *
 * @package ImpressCMS\Core\SetupSteps
 */
interface SetupStepInterface
{

	/**
	 * Execute setup step
	 *
	 * @param Module $module Current module that is installing
	 * @param OutputDecorator $output Output where to print messages
	 * @param array $params Extra params
	 *
	 * @return bool
	 */
	public function execute(Module $module, OutputDecorator $output, ...$params): bool;

	/**
	 * Get priority to use this step
	 *
	 * @return int
	 */
	public function getPriority(): int;

}