<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Legacy\LegacyRuntime;
use App\Tests\Support\LegacyWebTestCase;
use App\Tests\Support\TestSite;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * DBAL and the ImpressCMS database layer use one physical connection
 * (design D7), inside the Symfony kernel.
 */
#[RunTestsInSeparateProcesses]
#[Group('spec:shared-runtime')]
final class SharedConnectionTest extends LegacyWebTestCase
{
    private Connection $connection;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $this->connection = static::getContainer()->get('doctrine.dbal.default_connection');
        static::getContainer()->get(LegacyRuntime::class)->bootTo('module');
    }

    public function testLegacyLayersRunOnTheDbalConnection(): void
    {
        $pdo = $this->connection->getNativeConnection();

        self::assertSame($pdo, \icms::$db);
        self::assertSame($pdo, \icms::$xoopsDB->conn);
        self::assertInstanceOf(\Icms\Db\Mysql\Connection::class, $pdo, "ImpressCMS' own PDO class");
        self::assertSame(\PDO::ERRMODE_SILENT, $pdo->getAttribute(\PDO::ATTR_ERRMODE), 'legacy error mode');
    }

    /**
     * @scenario Transaction across layers
     */
    public function testRowWrittenByAnIpfHandlerIsVisibleToDbalUntilRollback(): void
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

        $row = $this->connection->fetchAssociative('SELECT rank_title FROM ' . TestSite::table('ranks') . ' WHERE rank_id = ?', [$id]);
        self::assertSame(['rank_title' => 'Integration rank'], $row, 'visible to DBAL inside the transaction');

        $this->connection->rollBack();

        self::assertFalse($this->connection->fetchAssociative('SELECT rank_id FROM ' . TestSite::table('ranks') . ' WHERE rank_id = ?', [$id]));
        $handler = icms_getModuleHandler('userrank', 'system');
        self::assertSame(0, $handler->getCount(new \icms_db_criteria_Item('rank_id', $id)), 'absent for the legacy layer too');
    }

    public function testDbalStillReportsErrorsAsExceptions(): void
    {
        try {
            $this->connection->executeQuery('SELECT * FROM table_that_does_not_exist');
            self::fail('Expected a DBAL exception.');
        } catch (\Doctrine\DBAL\Exception $e) {
            self::assertStringContainsString('table_that_does_not_exist', $e->getMessage());
        }

        self::assertFalse(\icms::$xoopsDB->query('SELECT * FROM table_that_does_not_exist'), 'legacy still gets false');
    }
}
