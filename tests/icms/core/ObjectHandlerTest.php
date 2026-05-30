<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class CoreObjectHandlerTest extends TestCase
{
	protected function setUp(): void
	{
		if (!class_exists('icms_core_ObjectHandler')) {
			require_once ICMS_LIBRARIES_PATH . '/icms/core/ObjectHandler.php';
		}
	}

	private function createHandler(icms_db_legacy_Database $db): object
	{
		return new class ($db) extends icms_core_ObjectHandler {
			public $lastCreated;
			public $lastFetched;
			public $lastInserted;
			public $lastDeleted;

			public function getDb()
			{
				return $this->db;
			}

			public function &create()
			{
				$this->lastCreated = (object) ['created' => true];
				return $this->lastCreated;
			}

			public function &get($int_id)
			{
				$this->lastFetched = (object) ['id' => (int) $int_id];
				return $this->lastFetched;
			}

			public function insert(&$object)
			{
				$this->lastInserted = $object;
				return true;
			}

			public function delete(&$object)
			{
				$this->lastDeleted = $object;
				return true;
			}
		};
	}

	public function testConstructorStoresDatabaseReference(): void
	{
		$db = new icms_db_legacy_Database();
		$handler = $this->createHandler($db);

		$this->assertSame($db, $handler->getDb());
	}

	public function testCreateAndGetReturnObjectsByReference(): void
	{
		$db = new icms_db_legacy_Database();
		$handler = $this->createHandler($db);

		$created = &$handler->create();
		$fetched = &$handler->get(42);

		$this->assertTrue($created->created);
		$this->assertSame(42, $fetched->id);
	}

	public function testInsertAndDeleteReceiveObjectByReference(): void
	{
		$db = new icms_db_legacy_Database();
		$handler = $this->createHandler($db);
		$object = (object) ['id' => 7];

		$this->assertTrue($handler->insert($object));
		$this->assertTrue($handler->delete($object));
		$this->assertSame($object, $handler->lastInserted);
		$this->assertSame($object, $handler->lastDeleted);
	}
}
