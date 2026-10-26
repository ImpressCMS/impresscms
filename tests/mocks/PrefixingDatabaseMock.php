<?php

declare(strict_types=1);

final class PrefixingDatabaseMock
{
	public function prefix(string $table): string
	{
		return 'test_prefix_' . $table;
	}
}
