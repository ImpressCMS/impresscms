<?php

namespace ImpressCMS\Tests\Core\Data;

use icms;
use ImpressCMS\Core\Data\BootConfig;
use PHPUnit\Framework\TestCase;

class BootConfigTest extends TestCase
{

	/**
	 * @var BootConfig
	 */
	private $bootConfig;

	public function getVarNames()
	{
		foreach ([
			'root_path',
			'url',

			'public_path',

			'plugins_path',
			'plugins_url',

			'preload_path',

			'modules_path',
			'modules_url',

			'libraries_path',
			'libraries_url',

			'include_path',
			'include_url',

			'upload_path',
			'upload_url',

			'theme_path',
			'theme_url',

			'storage_path',
			'cache_path',
			'logging_path',
			'purifier_path',
			'compile_path',

			'images_url',

			'editor_path',
			'editor_url',

			'imanager_folder_path',
			'imanager_folder_url',

			'images_set_url',
		] as $item) {
			yield $item => [$item];
		}
	}

	/**
	 * @dataProvider getVarNames
	 */
	public function testGet(string $varName): void
	{
		self::assertNotEmpty(
			$this->bootConfig->get($varName, null)
		);
	}

	public function testToArray() {
		self::assertIsArray(
			$this->bootConfig->toArray()
		);
	}

	public function testIsEnvDataExists() {
		self::assertIsBool(
			$this->bootConfig->isEnvDataExists()
		);
	}

	public function setUp(): void
	{
		$this->bootConfig = icms::getInstance()->get(BootConfig::class);

		parent::setUp();
	}

}