<?php

namespace ImpressCMS\Core\Providers;

use DirectoryIterator;
use FilesystemIterator;
use Generator;
use ImpressCMS\Core\Models\ModuleHandler;
use League\Container\Container;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\ServiceProviderInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionException;
use SplFileInfo;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\Translator;

/**
 * Adds possibility to use translator
 *
 * @package ImpressCMS\Core\Providers
 */
class TranslatorServiceProvider extends AbstractServiceProvider implements ServiceProviderInterface {

	/**
	 * @inheritdoc
	 */
	protected $provides = [
		'translator',
	];

	/**
	 * @inheritDoc
	 */
	public function register() {
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
	protected function getCacheFilename(): string {
		return ICMS_CACHE_PATH . '/translations.php';
	}

	/**
	 * Updates translation service data
	 *
	 * @throws ReflectionException
	 */
	protected function updateCache() {
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
			$lines[] = $this->generateLoaderLineForCache($translationLoader);
		}

		$lines[] = '';

		foreach ($this->getLanguageFilesCachedLines() as $line) {
			$lines[] = $line;
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
	protected function getLanguageFolders(): array {
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

	/**
	 * Gets language files cached lines
	 *
	 * @return Generator
	 */
	private function getLanguageFilesCachedLines() {
		foreach ($this->getLanguageFolders() as $folder) {
			foreach (new DirectoryIterator($folder) as $dirInfo) {
				if ($dirInfo->isDot() || !$dirInfo->isDir()) {
					continue;
				}
				foreach ($this->createTranslationFileIterator($dirInfo) as $fileInfo) {
					if ($fileInfo->isDir()) {
						continue;
					}
					yield $this->generateResourceLineForCache($fileInfo, $dirInfo);
				}
			}
		}
	}

	/**
	 * Generates resource line for cache
	 *
	 * @param SplFileInfo $fileInfo File info object for language file
	 * @param SplFileInfo $dirInfo Dir info for language file
	 *
	 * @return string
	 */
	private function generateResourceLineForCache(SplFileInfo $fileInfo, SplFileInfo $dirInfo): string {
		return sprintf(
			'$translator->addResource(%s, %s, %s, %s);',
			var_export(
				'.' . strtolower(
					$fileInfo->getExtension()
				),
				true
			),
			var_export(
				$fileInfo->getPath() . '/' . $fileInfo->getFileName(),
				true
			),
			var_export(
				$dirInfo->getFilename(),
				true
			),
			var_export(
				$this->makeDomainFromFileName($fileInfo, $dirInfo),
				true
			)
		);
	}

	/**
	 * Generates loader line for cache
	 *
	 * @param object $translationLoader TranslationLoader
	 *
	 * @return string
	 *
	 * @throws ReflectionException
	 */
	private function generateLoaderLineForCache($translationLoader): string {
		$reflection = new ReflectionClass($translationLoader);
		$loaderName = $this->makeLoaderName($reflection);
		return sprintf(
			'$translator->addLoader(%s, $container->get(\'\\\\\' .\\%s::class));',
			var_export(
				strtolower($loaderName),
				true
			),
			$reflection->getName()
		);
	}

	/**
	 * Creates translation file iterator
	 *
	 * @param DirectoryIterator $dirInfo Current direct iterator
	 *
	 * @return RecursiveIteratorIterator
	 */
	private function createTranslationFileIterator(DirectoryIterator $dirInfo): RecursiveIteratorIterator {
		return new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator(
				$dirInfo->getPath(),
				FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::SKIP_DOTS
			)
		);
	}

	/**
	 * Makes loader name from class short name
	 *
	 * @param ReflectionClass $reflection Current class reflection object
	 *
	 * @return string
	 */
	private function makeLoaderName(ReflectionClass $reflection): string {
		$shortName = $reflection->getShortName();
		if (substr($shortName, -strlen('FileLoader')) === 'FileLoader') {
			return '.' . str_replace('FileLoader', '', $shortName);
		}
		return str_replace('Loader', '', $shortName);
	}

	/**
	 * Makes translation domain name from filename
	 *
	 * @param SplFileInfo $fileInfo
	 * @param SplFileInfo $dirInfo
	 * @return string
	 */
	private function makeDomainFromFileName(SplFileInfo $fileInfo, SplFileInfo $dirInfo): string {
		return ltrim(
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
		);
	}
}