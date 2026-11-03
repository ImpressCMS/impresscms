<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Legacy\LegacyRuntime;
use App\Tests\Support\LegacyAppTestCase;
use App\Tests\Support\TestSite;
use Illuminate\Database\Connection;
use Illuminate\Database\QueryException;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
#[Group('spec:shared-runtime')]
final class SharedConnectionTest extends LegacyAppTestCase
{
    private Connection $connection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->connection = $this->app->make('db')->connection('icms');
        $this->app->make(LegacyRuntime::class)->bootTo('module');
    }

    public function testLegacyLayersRunOnTheLaravelConnection(): void
    {
        $pdo = $this->connection->getPdo();

        self::assertSame($pdo, \icms::$db);
        self::assertSame($pdo, \icms::$xoopsDB->conn);
        self::assertInstanceOf(\Icms\Db\Mysql\Connection::class, $pdo, "ImpressCMS' own PDO class");
        self::assertSame(\PDO::ERRMODE_SILENT, $pdo->getAttribute(\PDO::ATTR_ERRMODE), 'legacy error mode');
        self::assertSame(1, $pdo->getAttribute(\PDO::ATTR_EMULATE_PREPARES), "PDO's default, as ImpressCMS has it");
    }

    public function testTablePrefixIsTheLegacyPrefix(): void
    {
        $count = $this->connection->table('users')->count();

        self::assertSame(4, $count, 'webmaster, member, mediaadmin and mimetypeadmin in ' . TestSite::table('users'));
    }

    /**
     * @scenario Transaction across layers
     */
    public function testRowWrittenByAnIpfHandlerIsVisibleToLaravelUntilRollback(): void
    {
        $handler = icms_getModuleHandler('userrank', 'system');
        self::assertInstanceOf(\Icms\Ipf\Handler::class, $handler);

        $this->connection->beginTransaction();
        $rank = $handler->create();
        $rank->setVar('rank_title', 'Integration rank');
        $rank->setVar('rank_min', 1000);
        $rank->setVar('rank_max', 2000);
        $rank->setVar('rank_special', 0);
        $rank->setVar('rank_image', 'blank.gif');
        self::assertTrue($handler->insert($rank, true), implode(', ', $rank->getErrors()));
        $id = (int) $rank->getVar('rank_id');

        $title = $this->connection->table('ranks')->where('rank_id', $id)->value('rank_title');
        self::assertSame('Integration rank', $title, 'visible to Laravel inside the transaction');

        $this->connection->rollBack();

        self::assertFalse($this->connection->table('ranks')->where('rank_id', $id)->exists());
        $handler = icms_getModuleHandler('userrank', 'system');
        self::assertSame(0, $handler->getCount(new \icms_db_criteria_Item('rank_id', $id)), 'absent for the legacy layer too');
    }

    public function testLaravelStillReportsErrorsAsExceptions(): void
    {
        try {
            $this->connection->select('SELECT * FROM table_that_does_not_exist');
            self::fail('Expected a query exception.');
        } catch (QueryException $e) {
            self::assertStringContainsString('table_that_does_not_exist', $e->getMessage());
        }

        self::assertFalse(\icms::$xoopsDB->query('SELECT * FROM table_that_does_not_exist'), 'legacy still gets false');
    }
}
