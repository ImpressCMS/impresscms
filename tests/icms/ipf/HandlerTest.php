<?php

declare(strict_types=1);

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

// Load core first so constants are defined before ipf/Object.php guards run.
if (!defined('XOBJ_DTYPE_TXTBOX')) {
	require_once ICMS_LIBRARIES_PATH . '/icms/core/Object.php';
}

require_once __DIR__ . '/../../fixtures/IcmsCoreFilesystemStub.php';
require_once __DIR__ . '/../../fixtures/IcmsTestobjectObject.php';
require_once __DIR__ . '/../../traits/CreatesPrefixingDatabaseMock.php';

#[CoversClass(icms_ipf_Handler::class)]
final class IpfHandlerTest extends TestCase
{
	use CreatesPrefixingDatabaseMock;

	private string $virtualUploadPath;

	protected function setUp(): void
	{
		if (!class_exists('icms_core_ObjectHandler')) {
			require_once ICMS_LIBRARIES_PATH . '/icms/core/ObjectHandler.php';
		}
		if (!class_exists('icms_ipf_Handler')) {
			require_once ICMS_LIBRARIES_PATH . '/icms/ipf/Handler.php';
		}

		if (!defined('ICMS_UPLOAD_PATH')) {
			define('ICMS_UPLOAD_PATH', sys_get_temp_dir());
		}
		if (!defined('ICMS_UPLOAD_URL')) {
			define('ICMS_UPLOAD_URL', 'http://localhost/uploads');
		}

		vfsStream::setup('uploads');
		$this->virtualUploadPath = vfsStream::url('uploads');
	}

	public function testConstructorResolvesClassNameAndSetsPaths(): void
	{
		$db = $this->createMockDb();
		$handler = new icms_ipf_Handler($db, 'testobject', 'id', 'title', 'summary', 'icms');

		$this->assertSame('icms', $handler->_moduleName);
		$this->assertSame('test_prefix_icms_testobject', $handler->table);
		$this->assertSame('icms_testobject_Object', $handler->className);
		$this->assertSame('id', $handler->keyName);
		$this->assertSame('title', $handler->identifierName);
		$this->assertSame('summary', $handler->summaryName);
		$this->assertSame('testobject.php', $handler->_page);
		$this->assertSame(ICMS_ROOT_PATH . '/modules/icms/', $handler->_modulePath);
		$this->assertSame(ICMS_URL . '/modules/icms/', $handler->_moduleUrl);
		$this->assertSame(ICMS_UPLOAD_PATH . '/icms/', $handler->_uploadPath);
		$this->assertSame(ICMS_UPLOAD_URL . '/icms/', $handler->_uploadUrl);

		$ref = new ReflectionProperty($handler, 'db');
		$ref->setAccessible(true);
		$this->assertSame($db, $ref->getValue($handler));
	}

	public function testConstructorFallsBackToUcfirstClassName(): void
	{
		$db = $this->createMockDb();
		$handler = new icms_ipf_Handler($db, 'unknownitem', 'uid', 'name', '', 'unknown');

		$this->assertSame('UnknownUnknownitem', $handler->className);
	}

	public function testConstructorUsesSystemModuleWhenEmpty(): void
	{
		$db = $this->createMockDb();
		$handler = new icms_ipf_Handler($db, 'myitem', 'myid', '', '', '');

		$this->assertSame('system', $handler->_moduleName);
		$this->assertSame('test_prefix_myitem', $handler->table);
	}

	public function testCreateInitializesObjectAndSetsNewFlag(): void
	{
		$db = $this->createMockDb();
		$handler = new icms_ipf_Handler($db, 'testobject', 'id', 'title', '', 'icms');

		$obj = $handler->create();

		$this->assertInstanceOf(icms_testobject_Object::class, $obj);
		$this->assertSame($handler, $obj->handler);
		$this->assertTrue($obj->isNew());
	}

	public function testCreateWithIsNewFalseDoesNotSetNew(): void
	{
		$db = $this->createMockDb();
		$handler = new icms_ipf_Handler($db, 'testobject', 'id', 'title', '', 'icms');

		$obj = $handler->create(false);

		$this->assertInstanceOf(icms_testobject_Object::class, $obj);
		$this->assertFalse($obj->isNew());
	}

	public function testGetImageUrlReturnsComposedUrl(): void
	{
		$db = $this->createMockDb();
		$handler = new icms_ipf_Handler($db, 'testobject', 'id', 'title', '', 'icms');

		$expected = ICMS_UPLOAD_URL . '/icms/testobject/';
		$this->assertSame($expected, $handler->getImageUrl());
	}

	public function testGetImagePathReturnsComposedPathAndCallsStubbedMkdir(): void
	{
		$db = $this->createMockDb();
		$handler = new icms_ipf_Handler($db, 'testobject', 'id', 'title', '', 'icms');
		$handler->_uploadPath = $this->virtualUploadPath . '/icms/';

		$expected = $this->virtualUploadPath . '/icms/testobject/';
		$this->assertDirectoryDoesNotExist(vfsStream::url('uploads/icms/testobject'));
		$this->assertSame($expected, $handler->getImagePath());
		$this->assertDirectoryExists(vfsStream::url('uploads/icms/testobject'));
	}
}
