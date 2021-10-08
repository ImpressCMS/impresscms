<?php

namespace ImpressCMS\Core\Extensions\ExtensionDescriber;

/**
 * Interface that must implement all InfoReaders
 *
 * @package ImpressCMS\Core\Extensions\InfoReader
 */
interface ExtensionDescriberInterface
{

	/**
	 * Returns if this info reader can describe specific extension
	 *
	 * @param string $path Path where extension located
	 *
	 * @return bool
	 */
	public function canDescribe(string $path): bool;

	/**
	 * Describes extension (usually reads some info file)
	 *
	 * @param string $path Path where extension located
	 *
	 * @return array
	 */
	public function describe(string $path): array;

}