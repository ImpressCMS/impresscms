<?php


namespace ImpressCMS\Core\Extensions\ComposerDefinitions;

use League\Container\Container;

/**
 * Defines what methods should have composer definitions
 *
 * @package ImpressCMS\Core\ComposerDefinitions
 */
interface ComposerDefinitionInterface
{

	/**
	 * Loads definition
	 *
	 * @param Container $container Container used for loading
	 *
	 * @return null|mixed
	 */
	public function load(Container $container);

	/**
	 * Returns if definitions cache needs to be updated
	 *
	 * @param string $composerFile composer.json path
	 *
	 * @return bool
	 */
	public function needsUpdate(string $composerFile): bool;

	/**
	 * @param array $data
	 */
	public function updateCache(array $data): void;
}