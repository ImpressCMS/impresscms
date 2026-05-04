<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class ObjectTest extends TestCase
{
	protected function setUp(): void
	{
		if (!class_exists('icms_core_Object')) {
			require_once ICMS_LIBRARIES_PATH . '/icms/core/Object.php';
		}
	}

	public function testNewAndDirtyFlagsLifecycle(): void
	{
		$object = new icms_core_Object();

		$this->assertFalse($object->isNew());
		$this->assertFalse($object->isDirty());
		$this->assertFalse($object->isNewConfig());

		$object->setNew();
		$object->setDirty();
		$object->setNewConfig();

		$this->assertTrue($object->isNew());
		$this->assertTrue($object->isDirty());
		$this->assertTrue($object->isNewConfig());

		$object->unsetNew();
		$object->unsetDirty();
		$object->unsetNewConfig();

		$this->assertFalse($object->isNew());
		$this->assertFalse($object->isDirty());
		$this->assertFalse($object->isNewConfig());
	}

	public function testSetVarsAndFormVarsRespectDefinitionsAndPrefix(): void
	{
		$object = new icms_core_Object();
		$object->initVar('title', XOBJ_DTYPE_TXTBOX, '');
		$object->initVar('weight', XOBJ_DTYPE_INT, 0);

		$object->setVars([
			'title' => 'Hello',
			'weight' => '5',
		]);

		$this->assertSame('Hello', $object->getVar('title', 'n'));
		$this->assertSame('5', $object->getVar('weight', 'n'));
		$this->assertTrue($object->isDirty());

		$object->setFormVars([
			'xo_title' => 'From Form',
			'weight' => 999,
		], 'xo_');

		$this->assertSame('From Form', $object->getVar('title', 'n'));
		$this->assertSame('5', $object->getVar('weight', 'n'));
	}

	public function testGetVarTextboxFormatting(): void
	{
		$object = new icms_core_Object();
		$object->initVar('title', XOBJ_DTYPE_TXTBOX, '<b>ImpressCMS</b>');

		$this->assertSame('&lt;b&gt;ImpressCMS&lt;/b&gt;', $object->getVar('title', 's'));
		$this->assertSame('<b>ImpressCMS</b>', $object->getVar('title', 'n'));
	}

	public function testGetVarTextareaFormPreviewRemovesFilterMarkers(): void
	{
		$object = new icms_core_Object();
		$object->initVar('body', XOBJ_DTYPE_TXTAREA, '<!-- input filtered -->Hello<!-- filtered with htmlpurifier -->');

		$this->assertSame('Hello', $object->getVar('body', 'f'));
	}

	public function testGetVarOptionsMapsSelectedIndexesToLabels(): void
	{
		$object = new icms_core_Object();
		$object->initVar('status', XOBJ_DTYPE_INT, '1|3', false, null, 'Draft|Published|Archived');

		$this->assertSame('Draft, Archived', $object->getVar('status', 's'));
		$this->assertSame(['1', '3'], $object->getVar('status', 'e'));
	}

	#[DataProvider('scalarCastingProvider')]
	public function testCleanVarsCastsScalarTypes(int $dataType, $input, int $expected): void
	{
		$object = new icms_core_Object();
		$object->initVar('value', $dataType, 0);
		$object->setVar('value', $input);

		$this->assertTrue($object->cleanVars());
		$this->assertSame($expected, $object->cleanVars['value']);
		$this->assertFalse($object->isDirty());
	}

	#[DataProvider('urlNormalizationProvider')]
	public function testCleanVarsNormalizesUrls(string $input, string $expected): void
	{
		$object = new icms_core_Object();
		$object->initVar('website', XOBJ_DTYPE_URL, '');
		$object->setVar('website', $input);

		$this->assertTrue($object->cleanVars());
		$this->assertSame($expected, $object->cleanVars['website']);
	}

	#[DataProvider('emailValidationProvider')]
	public function testCleanVarsValidatesEmail(string $input, bool $isValid): void
	{
		$object = new icms_core_Object();
		$object->initVar('email', XOBJ_DTYPE_EMAIL, '', true);
		$object->setVar('email', $input);

		$this->assertSame($isValid, $object->cleanVars());

		if ($isValid) {
			$this->assertSame($input, $object->cleanVars['email']);
			$this->assertSame([], $object->getErrors());
			return;
		}

		$this->assertStringContainsString(_CORE_DB_INVALIDEMAIL, implode(' ', $object->getErrors()));
	}

	#[DataProvider('arrayNormalizationProvider')]
	public function testCleanVarsNormalizesArrayTypes(int $dataType, $input, $expected): void
	{
		$object = new icms_core_Object();
		$object->initVar('payload', $dataType, []);
		$object->setVar('payload', $input);

		$this->assertTrue($object->cleanVars());
		$this->assertSame($expected, $object->cleanVars['payload']);
	}

	#[DataProvider('timestampNormalizationProvider')]
	public function testCleanVarsNormalizesTimestampTypes(int $dataType, $input, int $expected): void
	{
		$object = new icms_core_Object();
		$object->initVar('date_value', $dataType, 0);
		$object->setVar('date_value', $input);

		$this->assertTrue($object->cleanVars());
		$this->assertSame($expected, $object->cleanVars['date_value']);
	}

	#[DataProvider('txtboxValidationProvider')]
	public function testCleanVarsTxtboxValidation(string $input, bool $required, int $maxlength, bool $expectedSuccess): void
	{
		$object = new icms_core_Object();
		$object->initVar('title', XOBJ_DTYPE_TXTBOX, '', $required, $maxlength);
		$object->setVar('title', $input);

		$this->assertSame($expectedSuccess, $object->cleanVars());

		if ($expectedSuccess) {
			$this->assertSame([], $object->getErrors());
			return;
		}

		$this->assertNotEmpty($object->getErrors());
		$this->assertStringContainsString('title', implode(' ', $object->getErrors()));
	}

	public function testGetValuesReturnsSubset(): void
	{
		$object = new icms_core_Object();
		$object->initVar('title', XOBJ_DTYPE_TXTBOX, 'Hello');
		$object->initVar('weight', XOBJ_DTYPE_INT, 10);

		$values = $object->getValues(['weight'], 'n');

		$this->assertSame(['weight' => 10], $values);
	}

	public static function scalarCastingProvider(): array
	{
		self::ensureCoreObjectLoaded();

		return [
			'int string' => [XOBJ_DTYPE_INT, '42', 42],
			'int with whitespace' => [XOBJ_DTYPE_INT, ' 007 ', 7],
			'int from float-like string' => [XOBJ_DTYPE_INT, '-3.9', -3],
			'int from non-numeric string' => [XOBJ_DTYPE_INT, 'abc', 0],
			'time-only from string' => [XOBJ_DTYPE_TIME_ONLY, '123', 123],
		];
	}

	public static function urlNormalizationProvider(): array
	{
		return [
			'missing scheme' => ['example.org', 'http://example.org'],
			'https remains unchanged' => ['https://example.org', 'https://example.org'],
			'uppercase http remains unchanged' => ['HTTP://example.org', 'HTTP://example.org'],
		];
	}

	public static function emailValidationProvider(): array
	{
		return [
			'valid email' => ['person@example.org', true],
			'invalid email' => ['not-an-email', false],
		];
	}

	public static function arrayNormalizationProvider(): array
	{
		self::ensureCoreObjectLoaded();

		return [
			'simple array with values' => [XOBJ_DTYPE_SIMPLE_ARRAY, ['one', 'two'], 'one|two'],
			'simple empty array' => [XOBJ_DTYPE_SIMPLE_ARRAY, [], ''],
			'serialized array from native array' => [XOBJ_DTYPE_ARRAY, ['x' => 'y'], serialize(['x' => 'y'])],
			'pre-serialized string preserved' => [XOBJ_DTYPE_ARRAY, 'a:1:{s:1:"x";s:1:"y";}', 'a:1:{s:1:"x";s:1:"y";}'],
		];
	}

	public static function timestampNormalizationProvider(): array
	{
		self::ensureCoreObjectLoaded();

		return [
			'stime from datetime string' => [XOBJ_DTYPE_STIME, '2024-01-02 03:04:05', strtotime('2024-01-02 03:04:05')],
			'mtime from date string' => [XOBJ_DTYPE_MTIME, '2024-01-02', strtotime('2024-01-02')],
			'ltime from integer' => [XOBJ_DTYPE_LTIME, 1700000000, 1700000000],
			'invalid date becomes zero' => [XOBJ_DTYPE_LTIME, 'invalid-date', 0],
		];
	}

	public static function txtboxValidationProvider(): array
	{
		return [
			'required and valid length' => ['abc', true, 5, true],
			'maxlength exceeded' => ['abcdef', false, 5, false],
			'required whitespace only fails' => ['   ', true, 10, false],
			'legacy empty string passes due early skip' => ['', true, 10, true],
		];
	}

	private static function ensureCoreObjectLoaded(): void
	{
		if (!defined('XOBJ_DTYPE_INT')) {
			require_once ICMS_LIBRARIES_PATH . '/icms/core/Object.php';
		}
	}
}



