<?php

namespace ImpressCMS\Core\Providers;

use Apix\Cache\Factory;
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
		'cache'
	];

	/**
	 * @inheritdoc
	 */
	public function register()
	{
		$this->getContainer()->add('cache', function () {
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
	}

}
