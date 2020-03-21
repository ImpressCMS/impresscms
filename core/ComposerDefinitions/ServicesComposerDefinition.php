<?php


namespace ImpressCMS\Core\ComposerDefinitions;

use League\Container\Container;

/**
 * Handles services definition from composer.json
 *
 * @package ImpressCMS\Core\ComposerDefinitions
 */
class ServicesComposerDefinition implements ComposerDefinitionInterface
{
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
	 * Gets filename for cached data
	 *
	 * @return string
	 */
	public function getCacheFilename(): string
	{
		return ICMS_CACHE_PATH . '/services.php';
	}

	/**
	 * @inheritDoc
	 */
	public function updateCache(array $data): void
	{
		$ret = '<?php' . PHP_EOL;
		$services = (isset($data['services']) && is_array($data['services'])) ? $data['services'] : [];
		foreach ($services as $alias => $serviceInfo) {
			$ret .= '$container' . PHP_EOL;
			$ret .= sprintf(
				'    ->add(%s, %s, %s)%s',
				var_export(
					$alias,
					true
				),
				var_export(
					$serviceInfo['class'] ?? $alias,
					true
				),
				var_export(
					isset($serviceInfo['shared']) ? (bool)$serviceInfo['shared'] : true,
					true
				),
				PHP_EOL
			);
			if (isset($serviceInfo['tags'])) {
				foreach ($serviceInfo['tags'] as $tag) {
					$ret .= '    ->addTag(' . var_export($tag, true) . ')' . PHP_EOL;
				}
			}
			if (isset($serviceInfo['arguments'])) {
				foreach ($serviceInfo['arguments'] as $argument) {
					$ret .= '    ->addArgument(' . var_export($argument, true) . ')' . PHP_EOL;
				}
			}
			if (isset($serviceInfo['methods'])) {
				foreach ($serviceInfo['methods'] as $method => $arguments) {
					$ret .= '    ->addMethodCall(' . var_export($method, true) . ',' . var_export((array)$arguments, true) . ')' . PHP_EOL;
				}
			}
			$ret = trim($ret) . ';' . PHP_EOL;
		}

		file_put_contents(
			$this->getCacheFilename(),
			$ret,
			LOCK_EX
		);
	}

	/**
	 * @inheritDoc
	 */
	public function load(Container $container)
	{
		require($this->getCacheFilename());
	}
}