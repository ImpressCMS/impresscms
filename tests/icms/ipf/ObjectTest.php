<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../fixtures/IcmsObjectEnvironmentStubs.php';

final class IpfObjectTest extends TestCase
{
	protected function setUp(): void
	{
		if (!class_exists('icms_core_Object')) {
			require_once ICMS_LIBRARIES_PATH . '/icms/core/Object.php';
		}

		if (!class_exists('icms_ipf_Object')) {
			require_once ICMS_LIBRARIES_PATH . '/icms/ipf/Object.php';
		}
	}

	public function testObjectCanBeConstructedWithHandler(): void
	{
		$handler = $this->createHandler();
		$object = new icms_ipf_Object($handler);

		$this->assertInstanceOf(icms_ipf_Object::class, $object);
		$this->assertSame($handler, $object->handler);
	}

	public function testInitVarAddsIpfMetadata(): void
	{
		$handler = $this->createHandler();
		$object = new icms_ipf_Object($handler);

		$object->initVar('title', XOBJ_DTYPE_TXTBOX, '', true, 255, '', false, 'Title', 'Description', true, true, true);

		$varInfo = $object->getVarInfo('title');

		$this->assertSame('Title', $varInfo['form_caption']);
		$this->assertSame('Description', $varInfo['form_dsc']);
		$this->assertTrue($varInfo['sortby']);
		$this->assertTrue($varInfo['persistent']);
		$this->assertTrue($varInfo['displayOnForm']);
		$this->assertTrue($varInfo['displayOnSingleView']);
		$this->assertFalse($varInfo['readonly']);
	}

	public function testSetControlAndGetControl(): void
	{
		$handler = $this->createHandler();
		$object = new icms_ipf_Object($handler);
		$object->initVar('status', XOBJ_DTYPE_INT, 0);

		$object->setControl('status', 'yesno');

		$this->assertSame(['name' => 'yesno'], $object->getControl('status'));
	}

	public function testIdAndTitleUseHandlerKeyAndIdentifierFields(): void
	{
		$handler = $this->createHandler([
			'keyName' => 'id',
			'identifierName' => 'title',
		]);
		$object = new icms_ipf_Object($handler);
		$object->initVar('id', XOBJ_DTYPE_INT, 0);
		$object->initVar('title', XOBJ_DTYPE_TXTBOX, '');

		$id = 12;
		$title = '<b>Example</b>';
		$object->setVar('id', $id);
		$object->setVar('title', $title);

		$this->assertSame(12, $object->id());
		$this->assertSame('&lt;b&gt;Example&lt;/b&gt;', $object->title('s'));
	}

	public function testToArrayReturnsVarsAndSkipsControllerLinksWhenNoIdentifier(): void
	{
		$handler = $this->createHandler([
			'identifierName' => '',
		]);
		$object = new icms_ipf_Object($handler);
		$object->initVar('title', XOBJ_DTYPE_TXTBOX, 'Example');
		$object->initVar('active', XOBJ_DTYPE_INT, 1);

		$array = $object->toArray();

		$this->assertSame('Example', $array['title']);
		$this->assertSame(1, $array['active']);
		$this->assertArrayNotHasKey('itemLink', $array);
	}

	private function createHandler(array $overrides = []): object
	{
		$handler = (object) array_merge([
			'keyName' => 'id',
			'identifierName' => '',
			'summaryName' => '',
			'_moduleName' => 'system',
			'_itemname' => 'item',
		], $overrides);

		return $handler;
	}
}
