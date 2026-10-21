<?php
/**
 * Icms\Core\Filesystem::installTrustDistribution(): installing the host from
 * the staging directory trust_dist/ into the trust path.
 *
 * @package icms\tests
 */

declare(strict_types=1);

namespace Icms\Tests\Unit\Core;

use Icms\Core\Filesystem;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('spec:host-deployment')]
final class TrustDistributionTest extends TestCase
{
    private string $dir;
    private string $root;
    private string $staging;
    private string $trust;

    protected function setUp(): void
    {
        $this->dir = str_replace('\\', '/', sys_get_temp_dir()) . '/icms-dist-' . bin2hex(random_bytes(4));
        $this->root = $this->dir . '/www';
        $this->staging = $this->root . '/trust_dist';
        $this->trust = $this->dir . '/trust';

        $this->write($this->staging . '/host.php', "<?php // host v2\n");
        $this->write($this->staging . '/composer.json', '{"name": "impresscms/impresscms"}');
        $this->write($this->staging . '/.env', "APP_ENV=prod\n");
        $this->write($this->staging . '/.htaccess', "Require all denied\n");
        $this->write($this->staging . '/vendor/autoload.php', "<?php // autoload v2\n");
        $this->write($this->staging . '/vendor/composer/autoload_static.php', "<?php // static v2\n");
        $this->write($this->staging . '/src/Kernel.php', "<?php // kernel v2\n");
        $this->write($this->staging . '/config/packages/framework.yaml', "framework: ~\n");
        mkdir($this->trust, 0777, true);
    }

    protected function tearDown(): void
    {
        if (is_dir($this->dir)) {
            @chmod($this->staging . '/src/Kernel.php', 0644);
            Filesystem::deleteRecursive($this->dir, true);
        }
    }

    /**
     * @scenario Fresh installation
     */
    public function testHostIsInstalledIntoAnEmptyTrustPath(): void
    {
        $result = $this->install();

        self::assertSame('ok', $result['status'], $result['message']);
        self::assertSame('', $result['warning']);
        self::assertStringEqualsFile($this->trust . '/host.php', "<?php // host v2\n");
        self::assertFileExists($this->trust . '/vendor/autoload.php');
        self::assertFileExists($this->trust . '/vendor/composer/autoload_static.php');
        self::assertFileExists($this->trust . '/src/Kernel.php');
        self::assertFileExists($this->trust . '/config/packages/framework.yaml');
        self::assertFileExists($this->trust . '/.htaccess');

        $env = (string) file_get_contents($this->trust . '/.env.local');
        self::assertMatchesRegularExpression('/^APP_ENV=prod$/m', $env);
        self::assertMatchesRegularExpression('/^APP_DEBUG=0$/m', $env);
        self::assertMatchesRegularExpression('/^APP_SECRET=[0-9a-f]{64}$/m', $env);
        self::assertStringContainsString("ICMS_LEGACY_ROOT='{$this->root}'", $env);

        self::assertDirectoryDoesNotExist($this->staging, 'the staging directory is removed');
        self::assertSame([], glob($this->trust . '/*.new') ?: [], 'no copies are left behind');
    }

    public function testEachInstallationGetsItsOwnSecret(): void
    {
        $this->install();
        $first = (string) file_get_contents($this->trust . '/.env.local');

        $other = $this->dir . '/other-trust';
        $this->setUp();
        Filesystem::installTrustDistribution($this->staging, $other, $this->root);

        self::assertNotSame(
            preg_replace('/^(?!APP_SECRET).*$/m', '', $first),
            preg_replace('/^(?!APP_SECRET).*$/m', '', (string) file_get_contents($other . '/.env.local')),
        );
    }

    /**
     * @scenario Reinstall keeps local state
     */
    public function testReinstallKeepsVarAndEnvLocalAndEmptiesTheCache(): void
    {
        $this->write($this->trust . '/host.php', "<?php // host v1\n");
        $this->write($this->trust . '/vendor/autoload.php', "<?php // autoload v1\n");
        $this->write($this->trust . '/vendor/old-package/gone.php', "<?php\n");
        $this->write($this->trust . '/.env.local', "APP_SECRET=mine\nICMS_LEGACY_ROOT='/srv/www'\n");
        $this->write($this->trust . '/var/log/prod.log', "kept\n");
        $this->write($this->trust . '/var/cache/prod/Container.php', "<?php // stale\n");
        // legacy entries of the trust path
        $this->write($this->trust . '/0123456789abcdef.php', "<?php // sdata\n");
        $this->write($this->trust . '/modules/protector/configs/badips.txt', '');

        $result = $this->install();

        self::assertSame('ok', $result['status'], $result['message']);
        self::assertStringEqualsFile($this->trust . '/host.php', "<?php // host v2\n");
        self::assertStringEqualsFile($this->trust . '/vendor/autoload.php', "<?php // autoload v2\n");
        self::assertFileDoesNotExist($this->trust . '/vendor/old-package/gone.php', 'vendor/ is replaced, not merged');
        self::assertStringEqualsFile($this->trust . '/.env.local', "APP_SECRET=mine\nICMS_LEGACY_ROOT='/srv/www'\n");
        self::assertStringEqualsFile($this->trust . '/var/log/prod.log', "kept\n");
        self::assertDirectoryDoesNotExist($this->trust . '/var/cache');
        self::assertFileExists($this->trust . '/0123456789abcdef.php');
        self::assertFileExists($this->trust . '/modules/protector/configs/badips.txt');
        self::assertDirectoryDoesNotExist($this->trust . '/vendor.pre-50', 'only a vendor/ without host is a dbversion 49 tree');
        self::assertSame([], glob($this->trust . '/*.old') ?: []);
    }

    public function testEnvLocalWithoutTheWebRootGetsIt(): void
    {
        $this->write($this->trust . '/.env.local', "APP_SECRET=mine\n");

        $this->install();

        self::assertSame("APP_SECRET=mine\nICMS_LEGACY_ROOT='{$this->root}'\n", file_get_contents($this->trust . '/.env.local'));
    }

    /**
     * @scenario Upgrade from a site with a moved vendor directory
     */
    public function testVendorMovedByDbversion49IsKeptAndItsStubRemoved(): void
    {
        $this->write($this->trust . '/vendor/autoload.php', "<?php // re-based by dbversion 49\n");
        $this->write($this->trust . '/libraries/Autoloader.php', "<?php require_once '/srv/www/libraries/Autoloader.php';\n");
        $this->write($this->root . '/vendor/autoload.php', "<?php // left in the web root\n");

        $result = $this->install();

        self::assertSame('ok', $result['status'], $result['message']);
        self::assertStringEqualsFile($this->trust . '/vendor.pre-50/autoload.php', "<?php // re-based by dbversion 49\n");
        self::assertStringEqualsFile($this->trust . '/vendor/autoload.php', "<?php // autoload v2\n");
        self::assertFileExists($this->trust . '/host.php');
        self::assertDirectoryDoesNotExist($this->trust . '/libraries');
        self::assertDirectoryDoesNotExist($this->root . '/vendor', 'no third-party code stays in the web root');
    }

    /**
     * @scenario Failed copy leaves the site intact
     */
    public function testFailedCopyChangesNothing(): void
    {
        if (DIRECTORY_SEPARATOR === '\\' || (function_exists('posix_geteuid') && posix_geteuid() === 0)) {
            self::markTestSkipped('Needs a file the process cannot read (POSIX permissions, not root).');
        }
        $this->write($this->trust . '/host.php', "<?php // host v1\n");
        $this->write($this->trust . '/vendor/autoload.php', "<?php // autoload v1\n");
        chmod($this->staging . '/src/Kernel.php', 0000);

        $result = @$this->install();

        self::assertSame('error_copy', $result['status']);
        self::assertStringContainsString('Nothing has been changed', $result['message']);
        self::assertStringEqualsFile($this->trust . '/host.php', "<?php // host v1\n");
        self::assertStringEqualsFile($this->trust . '/vendor/autoload.php', "<?php // autoload v1\n");
        self::assertSame([], glob($this->trust . '/*.new') ?: [], 'partial copies are removed');
        self::assertDirectoryExists($this->staging, 'the staging directory is kept for another attempt');
    }

    /**
     * @scenario Failed copy leaves the site intact
     */
    public function testIncompleteStagingDirectoryChangesNothing(): void
    {
        unlink($this->staging . '/vendor/autoload.php');
        $this->write($this->trust . '/host.php', "<?php // host v1\n");

        $result = $this->install();

        self::assertSame('error_dist', $result['status']);
        self::assertStringEqualsFile($this->trust . '/host.php', "<?php // host v1\n");
        self::assertDirectoryExists($this->staging);
    }

    public function testWithoutStagingDirectoryNothingIsDone(): void
    {
        Filesystem::deleteRecursive($this->staging, true);
        self::assertSame('nodist', $this->install()['status']);

        $this->write($this->trust . '/host.php', "<?php // host v1\n");
        self::assertSame('skipped', $this->install()['status']);
    }

    public function testDeferredActivationOnlyCopies(): void
    {
        $result = Filesystem::installTrustDistribution($this->staging, $this->trust, $this->root, true);

        self::assertSame('ok', $result['status']);
        // Activation runs at shutdown; until then only verified copies exist.
        self::assertFileExists($this->trust . '/host.php.new');
        self::assertFileExists($this->trust . '/vendor.new/autoload.php');
        self::assertFileDoesNotExist($this->trust . '/host.php');
        self::assertDirectoryExists($this->staging);
    }

    /**
     * @return array{status: string, message: string, warning: string, src: string, dest: string}
     */
    private function install(): array
    {
        return Filesystem::installTrustDistribution($this->staging, $this->trust, $this->root);
    }

    private function write(string $file, string $content): void
    {
        if (!is_dir(dirname($file))) {
            mkdir(dirname($file), 0777, true);
        }
        file_put_contents($file, $content);
    }
}
