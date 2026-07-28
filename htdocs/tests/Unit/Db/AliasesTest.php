<?php

declare(strict_types=1);

namespace Icms\Tests\Unit\Db;

use Icms\Tests\Support\LegacyAliasAssertions;
use Icms\Tests\Support\ProviderFactory;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class AliasesTest extends TestCase
{
	use LegacyAliasAssertions;

	/**
	 * @return iterable<array{0: string, 1: string}>
	 */
	public static function provider(): iterable
	{
		return ProviderFactory::forAliasPrefix('icms_db_');
	}

	/**
	 * Verify that the aliased legacy class name is still valid.
	 *
	 * @param string $alias
	 * @param string $modern
	 * @covers \Icms\Db\Connection
	 * @covers \Icms\Db\Factory
	 * @covers \Icms\Db\IConnection
	 * @covers \Icms\Db\IUtility
	 */
	#[DataProvider('provider')]
	public function testAlias(string $alias, string $modern): void
	{
		self::assertLegacyAlias($modern, $alias);
	}
}