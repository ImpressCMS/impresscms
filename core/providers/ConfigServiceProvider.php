<?php

namespace ImpressCMS\Core\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use icms;
use icms_config_Handler as Config;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

/**
 * Config service provider
 */
class ConfigServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{

	/**
	 * @inheritdoc
	 */
	protected $provides = [
		'config'
	];

	/**
	 * @inheritdoc
	 */
	public function register()
	{

	}

	/**
	 * @inheritDoc
	 */
	public function boot()
	{
		$this->getContainer()->add('config', function () {
			$instance = icms::handler('icms_config');
			$configs = $instance->getConfigsByCat(
				array(
					Config::CATEGORY_MAIN, Config::CATEGORY_USER, Config::CATEGORY_METAFOOTER, Config::CATEGORY_MAILER,
					Config::CATEGORY_AUTH, Config::CATEGORY_MULILANGUAGE, Config::CATEGORY_PERSONA, Config::CATEGORY_PLUGINS,
					Config::CATEGORY_CAPTCHA, Config::CATEGORY_SEARCH
				)
			);
			$GLOBALS['icmsConfig'] = $configs[Config::CATEGORY_MAIN];
			$GLOBALS['xoopsConfig'] =& $GLOBALS['icmsConfig'];
			$GLOBALS['icmsConfigUser'] = $configs[Config::CATEGORY_USER];
			$GLOBALS['icmsConfigMetaFooter'] = $configs[Config::CATEGORY_METAFOOTER];
			$GLOBALS['icmsConfigMailer'] = $configs[Config::CATEGORY_MAILER];
			$GLOBALS['icmsConfigAuth'] = $configs[Config::CATEGORY_AUTH];
			$GLOBALS['icmsConfigMultilang'] = $configs[Config::CATEGORY_MULILANGUAGE];
			$GLOBALS['icmsConfigPersona'] = $configs[Config::CATEGORY_PERSONA];
			$GLOBALS['icmsConfigPlugins'] = $configs[Config::CATEGORY_PLUGINS];
			$GLOBALS['icmsConfigCaptcha'] = $configs[Config::CATEGORY_CAPTCHA];
			$GLOBALS['icmsConfigSearch'] = $configs[Config::CATEGORY_SEARCH];
			return $instance;
		});
	}
}