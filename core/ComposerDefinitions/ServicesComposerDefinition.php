<?php


namespace ImpressCMS\Core\ComposerDefinitions;

use League\Container\Container;
use League\Container\ContainerAwareInterface;

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
		$ret .= '/**' . PHP_EOL;
		$ret .= ' * @var ' . Container::class . ' $container' . PHP_EOL;
		$ret .= ' */' . PHP_EOL;
		$ret .= '$container' . PHP_EOL;
		$ret .= "    ->add('container', \icms::getInstance());" . PHP_EOL;
		$services = (isset($data['services']) && is_array($data['services'])) ? $data['services'] : [];
		$containerAwareStr = '    ->addMethodCall(' . var_export('setLeagueContainer', true) . ',[' . var_export('container', true) . '])' . PHP_EOL;
		foreach ($services as $alias => $serviceInfo) {
			$ret .= '$container' . PHP_EOL;
			$class = $serviceInfo['class'] ?? $alias;
			$ret .= sprintf(
				'    ->add(%s, %s, %s)%s',
				var_export(
					$alias,
					true
				),
				var_export(
					$class,
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
			if ($this->implementsContainerAwareInterface($class)) {
				$ret .= $containerAwareStr;
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
	 * Checks if class implements ContainerAwareInterface
	 *
	 * @param string $class Class to check
	 *
	 * @return bool
	 *
	 * @throws \ReflectionException
	 */
	protected function implementsContainerAwareInterface(string $class): bool
	{
		$reflect = new \ReflectionClass($class);
		return $reflect->implementsInterface(ContainerAwareInterface::class);
	}

	/**
	 * @inheritDoc
	 */
	public function load(Container $container)
	{
		require($this->getCacheFilename());
	}
}