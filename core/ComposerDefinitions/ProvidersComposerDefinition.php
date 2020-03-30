<?php


namespace ImpressCMS\Core\ComposerDefinitions;

use League\Container\Container;

/**
 * Defines how 'providers' key in composer.json is handled
 *
 * @package ImpressCMS\Core\ComposerDefinitions
 */
class ProvidersComposerDefinition implements ComposerDefinitionInterface
{
	/**
	 * Gets filename for cached data
	 *
	 * @return string
	 */
	public function getCacheFilename(): string
	{
		return ICMS_CACHE_PATH . '/providers.php';
	}

	/**
	 * @inheritDoc
	 */
	public function needsUpdate(string $composerPath): bool
	{
		$filename = $this->getCacheFilename();

		return (!file_exists($filename)) ||
			(filemtime($filename) < filemtime($composerPath . '/composer.json')) ||
			(filemtime($filename) < filemtime($composerPath . '/composer.lock'));
	}

	/**
	 * @inheritDoc
	 */
	public function updateCache(array $data): void
	{
		$ret = '<?php' . PHP_EOL;
		$ret .= '/**' . PHP_EOL;
		$ret .= ' * @var ' . Container::class . ' $container' . PHP_EOL;
		$ret .= ' */' . PHP_EOL;
		$ret .= '$container' . PHP_EOL;
		foreach ($this->generateData($data) as $provider) {
			$ret .= '    ->addServiceProvider(' . var_export($provider, true) . ')' . PHP_EOL;
		}
		$ret = trim($ret) . ';';

		file_put_contents(
			$this->getCacheFilename(),
			$ret,
			LOCK_EX
		);
	}

	/**
	 * Generates data for cache
	 *
	 * @param array $data Composer.json data array
	 *
	 * @return string[]
	 */
	protected function generateData(array $data): array
	{
		if (!isset($data['providers']) || !is_array($data['providers'])) {
			return [];
		}
		return array_map('strval', $data['providers']);
	}

	/**
	 * @inheritDoc
	 */
	public function load(Container $container)
	{
		require($this->getCacheFilename());
	}
}