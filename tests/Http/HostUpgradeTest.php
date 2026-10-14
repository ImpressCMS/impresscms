<?php

declare(strict_types=1);

namespace App\Tests\Http;

use App\Tests\Support\HttpTestCase;
use App\Tests\Support\ServerProcess;
use App\Tests\Support\TestSite;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Upgrading a site from before the host: its trust path has neither the host
 * nor vendor/, the system module is at dbversion 48, and the files of this
 * release (with the staging directory htdocs/trust_dist from bin/build-dist)
 * have just been uploaded. The system module update installs the host.
 *
 * Served like a site without any server configuration (routing level 2, no
 * environment variables visible to PHP), so after the update the host runs
 * with the settings it wrote to .env.local. Runs only when htdocs/trust_dist
 * exists; the update removes it, so build it again for another run.
 */
#[Group('spec:host-deployment')]
final class HostUpgradeTest extends HttpTestCase
{
    private static function siteDir(): string
    {
        return TestSite::varDir() . '/upgrade';
    }

    private static function trust(): string
    {
        return self::siteDir() . '/trust';
    }

    private static function staging(): string
    {
        return TestSite::legacyRoot() . '/trust_dist';
    }

    public static function setUpBeforeClass(): void
    {
        if (!is_file(self::staging() . '/host.php') || ServerProcess::externalBaseUri() !== null) {
            return;
        }

        // the test site as a site from before the host
        $filesystem = new Filesystem();
        $filesystem->remove(self::siteDir());
        $filesystem->mkdir(self::trust() . '/cache/htmlpurifier');
        $filesystem->dumpFile(
            self::siteDir() . '/mainfile.php',
            str_replace(TestSite::trustPath(), self::trust(), (string) file_get_contents(TestSite::mainfile())),
        );

        parent::setUpBeforeClass();
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
        if (is_dir(self::siteDir())) {
            (new Filesystem())->remove(self::siteDir());
            TestSite::restoreSnapshot();
        }
    }

    protected static function createServer(): ServerProcess
    {
        return new ServerProcess(
            TestSite::legacyRoot(),
            null,
            ['ICMS_MAINFILE' => self::siteDir() . '/mainfile.php'],
            null,
            // what PHP-FPM, mod_php and FrankenPHP commonly do: no environment in $_ENV
            ['variables_order' => 'GPCS'],
        );
    }

    protected function setUp(): void
    {
        if (static::$server === null) {
            self::markTestSkipped('Needs htdocs/trust_dist from bin/build-dist and a local server.');
        }
        parent::setUp();
    }

    /**
     * @scenario Upgrade from a release without Composer
     * @scenario Installed host files
     * @scenario Server without environment passing
     */
    public function testSystemModuleUpdateInstallsTheHost(): void
    {
        TestSite::restoreSnapshot();
        TestSite::connection()->exec('UPDATE ' . TestSite::table('modules') . " SET dbversion = 48 WHERE dirname = 'system'");

        // Before the update: legacy runs on the staging directory's dependencies,
        // without the host (/up is not a Symfony route yet: index.php shows the home page).
        $home = $this->request('GET', '/');
        self::assertSame(200, $home->getStatusCode(), self::serverLog());
        self::assertStringContainsString(TestSite::SITE_NAME, $home->getContent());
        self::assertNotSame('ok', $this->request('GET', '/up')->getContent());

        self::assertSame(302, $this->loginAsWebmaster()->getStatusCode(), self::serverLog());
        // a webmaster of an outdated site is sent to the system module update
        $admin = $this->request('GET', '/admin.php');
        self::assertStringContainsString('op=update&module=system', (string) $admin->getHeader('Location'), self::serverLog());
        $crawler = $this->browser->request('GET', static::url('/modules/system/admin.php?fct=modulesadmin&op=update&module=system'));
        self::assertSame(200, $this->browser->getInternalResponse()->getStatusCode(), self::serverLog());
        $this->browser->submit($crawler->filterXPath('//input[@name="confirm_submit"]')->form());
        $update = $this->browser->getInternalResponse();

        self::assertSame(200, $update->getStatusCode(), self::serverLog());
        self::assertStringContainsString('The host files have been copied to the trust path', $update->getContent(), self::serverLog());
        self::assertStringNotContainsString('ERROR', $update->getContent());

        // After the update (activated at the end of that request)
        foreach (['host.php', 'composer.json', 'vendor/autoload.php', 'src/Kernel.php', 'config/bundles.php', '.env.local'] as $file) {
            self::assertFileExists(self::trust() . '/' . $file);
        }
        self::assertMatchesRegularExpression('/^APP_SECRET=[0-9a-f]{64}$/m', (string) file_get_contents(self::trust() . '/.env.local'));
        self::assertDirectoryDoesNotExist(self::staging(), 'the staging directory is removed');
        self::assertDirectoryDoesNotExist(TestSite::legacyRoot() . '/trust_dist');
        self::assertSame(
            '50',
            (string) TestSite::connection()->query('SELECT dbversion FROM ' . TestSite::table('modules') . " WHERE dirname = 'system'")->fetchColumn(),
        );

        // The host now answers, in production mode from .env.local, and the
        // webmaster is still logged in.
        $up = $this->request('GET', '/up');
        self::assertSame(200, $up->getStatusCode(), self::serverLog());
        self::assertSame('ok', $up->getContent());
        $home = $this->request('GET', '/');
        self::assertSame(200, $home->getStatusCode(), self::serverLog());
        self::assertStringContainsString(TestSite::SITE_NAME, $home->getContent());
        $admin = $this->request('GET', '/admin.php');
        self::assertSame(200, $admin->getStatusCode(), 'still logged in: ' . $admin->getHeader('Location') . ' ' . json_encode($this->browser->getCookieJar()->allValues(static::url('/'))) . self::serverLog());
    }
}
