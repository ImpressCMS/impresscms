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
		return [
			'Connection'                   => ['Icms\\Db\\Connection', 'icms_db_Connection'],
			'Factory'                   => ['Icms\\Db\\Factory', 'icms_db_Factory'],
			'IConnection'                   => ['Icms\\Db\\IConnection', 'icms_db_IConnection'],
			'IUtility'                   => ['Icms\\Db\\IUtility', 'icms_db_IUtility'],
			'Criteria Item'                   => ['Icms\\Db\\Criteria\\Item', 'icms_db_Criteria_Item'],
			'Criteria Compo'                   => ['Icms\\Db\\Criteria\\Compo', 'icms_db_Criteria_Compo'],
			'Criteria Element'                   => ['Icms\\Db\\Criteria\\Element', 'icms_db_Criteria_Element'],
			'Legacy Database'                   => ['Icms\\Db\\Legacy\\Database', 'icms_db_Legacy_Database'],
			'Legacy DB Factory'                   => ['Icms\\Db\\Legacy\\Factory', 'icms_db_Legacy_Factory'],
			'Legacy MySQL Database'                   => ['Icms\\Db\\Legacy\\MySQL\\Database', 'icms_db_Legacy_MySQL_Database'],
			'Legacy MySQL Database Proxy'                   => ['Icms\\Db\\Legacy\\MySQL\\Proxy', 'icms_db_Legacy_MySQL_Proxy'],
			'Legacy MySQL Database Safe'                   => ['Icms\\Db\\Legacy\\MySQL\\Safe', 'icms_db_Legacy_MySQL_Safe'],
			'Legacy MySQL Database Utility'                   => ['Icms\\Db\\Legacy\\MySQL\\Utility', 'icms_db_Legacy_MySQL_Utility'],
			'Legacy Updater Handler'                   => ['Icms\\Db\\Legacy\\Updater\\Handler', 'icms_db_Legacy_Updater_Handler'],
			'Legacy Updater Table'                   => ['Icms\\Db\\Legacy\\Updater\\Table', 'icms_db_Legacy_Updater_Table'],
			'MySQL Connection'                   => ['Icms\\Db\\MySQL\\Connection', 'icms_db_MySQL_Connection'],
			'MySQL Utility'                   => ['Icms\\Db\\MySQL\\Utility', 'icms_db_MySQL_Utility'],

			];
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