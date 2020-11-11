<?php

namespace ImpressCMS\Core\Providers;

use DirectoryIterator;
use FilesystemIterator;
use ImpressCMS\Core\Models\ModuleHandler;
use League\Container\Container;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\ServiceProviderInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionException;
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
		if (!file_exists($this->getCacheFilename())) {
			$this->updateCache();
		}

		global $icmsConfig;

		$translator = new Translator($icmsConfig['language']);
		$translator->setFallbackLocales(['english']);
		$container = $this->getContainer();

		include $this->getCacheFilename();

		$this->getLeagueContainer()->add('translator', $translator);
	}

	/**
	 * Gets filename for cached data
	 *
	 * @return string
	 */
	protected function getCacheFilename(): string
	{
		return ICMS_CACHE_PATH . '/translations.php';
	}

	/**
	 * Updates translation service data
	 *
	 * @throws ReflectionException
	 */
	protected function updateCache()
	{
		$lines = [
			'<?php',
			'',
			'/**',
			' * This file is generated and updated automatically. Do not edit!',
			' *',
			' * @var ' . Translator::class . ' $translator',
			' * @var ' . LoaderInterface::class . ' $translationLoader',
			' * @var ' . Container::class . ' $container',
			' */',
			'',
		];

		foreach ($this->getContainer()->get('translation.loader') as $translationLoader) {
			$reflection = new ReflectionClass($translationLoader);
			$shortName = $reflection->getShortName();
			if (substr($shortName, -strlen('FileLoader')) === 'FileLoader') {
				$loaderName = '.' . str_replace('FileLoader', '', $reflection->getShortName());
			} else {
				$loaderName = str_replace('Loader', '', $reflection->getShortName());
			}
			$lines[] = sprintf(
				'$translator->addLoader(%s, $container->get(\'\\\\\' .\\%s::class));',
				var_export(
					strtolower($loaderName),
					true
				),
				$reflection->getName()
			);
		}

		$lines[] = '';

		foreach ($this->getLanguageFolders() as $folder) {
			foreach (new DirectoryIterator($folder) as $dirInfo) {
				if ($dirInfo->isDot() || !$dirInfo->isDir()) {
					continue;
				}
				foreach (
					new RecursiveIteratorIterator(
						new RecursiveDirectoryIterator($dirInfo->getPath(), FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::SKIP_DOTS)
					)
					as $fileInfo
				) {
					if ($fileInfo->isDir()) {
						continue;
					}
					$lines[] = sprintf(
						'$translator->addResource(%s, %s, %s, %s);',
						var_export(
							'.' . strtolower(
								$fileInfo->getExtension()
							),
							true
						),
						var_export(
							$fileInfo->getPath() . '/' .
							$fileInfo->getFileName(),
							true
						),
						var_export(
							$dirInfo->getFilename(),
							true
						),
						var_export(
							ltrim(
							str_replace(
								['/', '\\'],
								'.',
								mb_substr(
									$fileInfo->getPath(),
									mb_strlen(
										$dirInfo->getPath() . '/' . $dirInfo->getFilename()
									)
								). '/' . $fileInfo->getBaseName('.' . $fileInfo->getExtension())
							),
								'.'
							),
							true
						)
					);
				}
			}
		}

		file_put_contents(
			$this->getCacheFilename(),
			implode(PHP_EOL, $lines),
			LOCK_EX
		);
	}

	/**
	 * Gets all folders where
	 *
	 * @return string[]
	 */
	protected function getLanguageFolders()
	{
		$folders = [
			ICMS_ROOT_PATH . '/language/'
		];
		foreach (ModuleHandler::getActive() as $moduleName) {
			$path = ICMS_ROOT_PATH . '/modules/' . $moduleName . '/language/';
			if (file_exists($path) && is_dir($path)) {
				$folders[] = $path;
			}
		}
		return $folders;
	}
}