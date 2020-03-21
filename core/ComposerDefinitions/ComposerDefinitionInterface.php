<?php


namespace ImpressCMS\Core\ComposerDefinitions;

use League\Container\Container;
use Psr\Container\ContainerInterface;

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
	 * @param ContainerInterface $container Container used for loading
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