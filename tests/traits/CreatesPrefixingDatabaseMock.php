<?php

declare(strict_types=1);

require_once __DIR__ . '/../mocks/PrefixingDatabaseMock.php';

trait CreatesPrefixingDatabaseMock
{
	private function createMockDb(): PrefixingDatabaseMock
	{
		return new PrefixingDatabaseMock();
	}
}
