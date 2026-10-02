<?php

namespace Icms\Tests\Unit\Db;

use Icms\Tests\Support\ProviderFactory;
use Icms\Tests\Support\LegacyAliasAssertions;

class AliasesTest extends \PHPUnit\Framework\TestCase
{
    use LegacyAliasAssertions;

    /**
     * @return iterable<string>
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
    public function testAlias(string $alias, string $modern): void
    {
        self::assertLegacyAlias($alias, $modern);
    }
}
