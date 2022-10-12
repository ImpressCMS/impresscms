<?php

namespace ImpressCMS\Core\Extensions\SetupSteps;

use ImpressCMS\Core\Extensions\ExtensionDescriber\DescribedItemInfoInterface;
use ImpressCMS\Core\Extensions\ExtensionDescriber\ExtensionDescriberInterface;
use ImpressCMS\Core\Models\Module;

/**
 * Interface that defines how setup step works
 *
 * @package ImpressCMS\Core\SetupSteps
 */
interface SetupStepInterface
{

	/**
	 * Get priority to use this step
	 *
	 * @return int
	 */
	public function getPriority(): int;

}