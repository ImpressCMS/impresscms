<?php

declare(strict_types=1);

namespace App\Tests\Http;

use App\Tests\Http\Characterization\SnapshotNormalizer;
use App\Tests\Support\HttpTestCase;
use App\Tests\Support\TestSite;
use PHPUnit\Framework\Attributes\Group;

/**
 * An unmodified IPF module page through the bridge renders as before, and
 * loadService-<name> listeners that a preload registers still run. A probe
 * preload, installed for this test class only, reports each listener call in
 * a response header.
 */
#[Group('spec:shared-runtime')]
final class ModuleBootTest extends HttpTestCase
{
    private const PROBE = 'zzicmstestprobe';

    public static function setUpBeforeClass(): void
    {
        file_put_contents(self::probeFile(), <<<'PHP'
            <?php
            // Test probe (tests/Http/ModuleBootTest.php); removed after the test.
            class IcmsPreloadZzicmstestprobe extends icms_preload_Item {
                public function eventStartCoreBoot() {
                    foreach (['db', 'config', 'session'] as $service) {
                        icms_Event::attach('icms', 'loadService-' . $service, static function ($parameters) use ($service) {
                            header('X-Test-LoadService-' . $service . ': ' . get_class($parameters['service']), false);
                        });
                    }
                }
            }
            PHP);

        parent::setUpBeforeClass();
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
        @unlink(self::probeFile());
    }

    private static function probeFile(): string
    {
        return TestSite::legacyRoot() . '/plugins/preloads/' . self::PROBE . '.php';
    }

    /**
     * @scenario Module boots as before
     */
    public function testIpfModulePageRendersAsBeforeAndLoadServiceListenersRun(): void
    {
        TestSite::restoreSnapshot();
        $this->loginAsWebmaster();
        $response = $this->request('GET', '/modules/system/admin.php?fct=userrank');

        self::assertSame(200, $response->getStatusCode(), self::serverLog());
        self::assertSame(\Icms\Db\Mysql\Connection::class, $response->getHeader('X-Test-LoadService-db'));
        self::assertSame(\Icms\Config\Handler::class, $response->getHeader('X-Test-LoadService-config'));
        self::assertSame(\Icms\Core\Session::class, $response->getHeader('X-Test-LoadService-session'));

        $transcript = (new SnapshotNormalizer(static::$server->baseUri()))
            ->transcript('GET /modules/system/admin.php?fct=userrank (webmaster)', $response);
        self::assertSame(
            str_replace("\r\n", "\n", (string) file_get_contents(__DIR__ . '/Characterization/__snapshots__/ipf-admin-list-webmaster.txt')),
            $transcript,
            'same output as the characterization snapshot',
        );
    }
}
