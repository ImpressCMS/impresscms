<?php


namespace ImpressCMS\Core\SetupSteps\Module\Uninstall;

use ImpressCMS\Core\SetupSteps\Module\Install\CacheClearSetupStep as OriginalCacheClearSetupStep;
use ImpressCMS\Core\Extensions\SetupSteps\SetupStepInterface;

/**
 * Clears cache
 *
 * @package ImpressCMS\Core\SetupSteps\Module\Uninstall
 */
class CacheClearSetupStep extends OriginalCacheClearSetupStep implements SetupStepInterface
{

}
