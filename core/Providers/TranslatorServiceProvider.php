<?php

namespace ImpressCMS\Core\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\ServiceProviderInterface;
use ReflectionClass;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\Translator;

/**
 * Adds possibility to use translator
 *
 * @package ImpressCMS\Core\Providers
 */
class TranslatorServiceProvider extends AbstractServiceProvider implements ServiceProviderInterface
{

	/**
	 * @inheritdoc
	 */
	protected $provides = [
		'translator',
	];

	/**
	 * @inheritDoc
	 */
	public function register()
	{
		global $icmsConfig;
		$translator = new Translator($icmsConfig['language']);
		$translator->setFallbackLocales(['english']);
		/**
		 * @var LoaderInterface $translationLoader
		 */
		foreach ($this->getContainer()->get('translation.loader') as $translationLoader) {
			$reflection = new ReflectionClass($translationLoader);
			$shortName = $reflection->getShortName();
			if (substr($shortName, -strlen($shortName)) === 'FileLoader') {
				$loaderName = '.' . str_replace('FileLoader', '', $reflection->getShortName());
			} else {
				$loaderName = str_replace('Loader', '', $reflection->getShortName());
			}
			$translator->addLoader(strtolower($loaderName), $translationLoader);
		}
		$this->getLeagueContainer()->add('translator', $translator);
	}
}