<?php

namespace ImpressCMS\Core\Extensions\ExtensionDescriber;

use ArrayAccess;

/**
 * Class that describes theme
 */
class ThemeInfo implements DescribedItemInfoInterface
{
	use ArrayAccessTrait;

	public string $name;
	public string $version;
	public string $description;
	public bool $hasAdmin;
	public bool $hasUser;
	public array $screenshots = [
		'user' => null,
		'admin' => null,
	];
	public string $license;
	public string $path;
	public array $assets = [];
}