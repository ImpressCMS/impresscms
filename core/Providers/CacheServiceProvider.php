<?php

namespace ImpressCMS\Core\Providers;

use Apix\SimpleCache\Factory;
use League\Container\ServiceProvider\AbstractServiceProvider;


/**
 * Cache service provider
 */
class CacheServiceProvider extends AbstractServiceProvider
{

	/**
	 * @inheritdoc
	 */
	protected $provides = [
		'cache',
		'cache.simple',
	];

	/**
	 * @inheritdoc
	 */
	public function register()
	{
		$this->getContainer()->add('cache.simple', function () {
			return Factory::getPool(
				'files',
				[
					'directory' => ICMS_CACHE_PATH,
					'locking' => true,
					'prefix_key' => 'icms'
				],
				true
			);
		});
		$this->getContainer()->add('cache', function () {
			return \Apix\Cache\Factory::getPool(
				'files',
				[
					'directory' => ICMS_CACHE_PATH,
					'locking' => true,
					'prefix_key' => 'icms'
				],
				true
			);
		});
	}

}
