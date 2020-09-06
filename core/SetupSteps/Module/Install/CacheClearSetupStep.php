<?php


namespace ImpressCMS\Core\SetupSteps\Module\Install;

use Apix\Cache\PsrCache\Pool;
use icms_module_Object;
use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Extensions\SetupSteps\SetupStepInterface;

/**
 * Clears cache
 *
 * @package ImpressCMS\Core\SetupSteps\Module\Install
 */
class CacheClearSetupStep implements SetupStepInterface
{
	/**
	 * CacheClearSetupStep constructor.
	 *
	 * @param Pool $cachePool
	 */
	public function __construct(Pool $cachePool)
	{
		$this->cache = $cachePool;
	}

	/**
	 * @inheritDoc
	 */
	public function execute(icms_module_Object $module, OutputDecorator $output, ...$params): bool
	{
		$output->info(_MD_AM_CLEARING_CACHE);

		$this->cache->clear();
		\icms::getInstance()->boot(false);

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getPriority(): int
	{
		return PHP_INT_MAX;
	}
}
