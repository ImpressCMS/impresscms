<?php

declare(strict_types=1);

namespace App\Tests\Unit\Host;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * htdocs/include/host.php: how the web root's index.php, the bootstrap and the
 * installer find the trust path, the Composer autoloader and the host.
 */
#[Group('spec:host-deployment')]
final class HostDiscoveryTest extends TestCase
{
    private string $dir;
    private string $root;
    private string $trust;

    public static function setUpBeforeClass(): void
    {
        require_once dirname(__DIR__, 3) . '/htdocs/include/host.php';
    }

    protected function setUp(): void
    {
        $this->dir = str_replace('\\', '/', sys_get_temp_dir()) . '/icms-host-' . bin2hex(random_bytes(4));
        $this->root = $this->dir . '/www';
        $this->trust = $this->dir . '/trust';
        (new Filesystem())->mkdir([$this->root, $this->trust]);
        putenv('ICMS_MAINFILE');
    }

    protected function tearDown(): void
    {
        putenv('ICMS_MAINFILE');
        (new Filesystem())->remove($this->dir);
    }

    /**
     * @scenario Trust path is read without executing mainfile.php
     */
    public function testTrustPathIsReadFromAnInstalledMainfileWithoutRunningIt(): void
    {
        // The installer's template: a commented example first, then the value;
        // the exit() proves the file is never executed.
        $this->writeMainfile("<?php\nexit('executed');\n// Example: define('XOOPS_TRUST_PATH', '/path/to/trust/directory');\n\tdefine('XOOPS_TRUST_PATH', '{$this->trust}');\n");

        self::assertSame($this->trust, icms_host_trust_path($this->root));
    }

    /**
     * @scenario Trust path is read without executing mainfile.php
     */
    public function testTheStubFollowsIcmsMainfile(): void
    {
        $this->writeMainfile("<?php\n// Example: define('XOOPS_TRUST_PATH', '/path/to/trust/directory');\nheader('Location: install/index.php');\n");
        self::assertNull(icms_host_trust_path($this->root), 'an uninstalled site has no trust path');

        file_put_contents($this->dir . '/test-mainfile.php', "<?php\ndefine(\"XOOPS_TRUST_PATH\", \"{$this->trust}/\");\n");
        putenv('ICMS_MAINFILE=' . $this->dir . '/test-mainfile.php');

        self::assertSame($this->trust, icms_host_trust_path($this->root));
    }

    public function testWindowsPathsAndTheSdataVariant(): void
    {
        $this->writeMainfile("<?php\ndefine( 'XOOPS_TRUST_PATH', 'C:\\\\sites\\\\trust' );\ninclude_once XOOPS_TRUST_PATH . '/0123abcd.php';\ndefine( 'XOOPS_DB_HOST', SDATA_DB_HOST );\n");

        self::assertSame('C:/sites/trust', icms_host_trust_path($this->root));
    }

    public function testDependenciesComeFromTheTrustPathThenTheStagingDirectoryThenTheWebRoot(): void
    {
        self::assertNull(icms_host_vendor_autoload($this->root, $this->trust));

        $this->touch($this->root . '/vendor/autoload.php');
        self::assertSame($this->root . '/vendor/autoload.php', icms_host_vendor_autoload($this->root, $this->trust));

        $this->touch($this->root . '/trust_dist/vendor/autoload.php');
        self::assertSame($this->root . '/trust_dist/vendor/autoload.php', icms_host_vendor_autoload($this->root, $this->trust));

        $this->touch($this->trust . '/vendor/autoload.php');
        self::assertSame($this->trust . '/vendor/autoload.php', icms_host_vendor_autoload($this->root, $this->trust));
        self::assertSame($this->root . '/trust_dist/vendor/autoload.php', icms_host_vendor_autoload($this->root, null));
    }

    /**
     * @scenario Trust path without a host
     */
    public function testTheHostIsOnlyFoundWhenItIsInstalledInTheTrustPath(): void
    {
        $this->writeMainfile("<?php\ndefine('XOOPS_TRUST_PATH', '{$this->trust}');\n");
        self::assertNull(icms_host_entry($this->root), 'empty trust path');

        $this->touch($this->trust . '/host.php');
        self::assertNull(icms_host_entry($this->root), 'host.php without vendor/');

        $this->touch($this->trust . '/vendor/autoload.php');
        self::assertSame($this->trust . '/host.php', icms_host_entry($this->root));
    }

    public function testATrustPathInsideThePublicDirectoriesIsPublic(): void
    {
        self::assertTrue(icms_host_trust_path_is_public($this->root . '/trust_abc', $this->root));
        self::assertTrue(icms_host_trust_path_is_public($this->root . '/trust_abc', '', $this->dir));
        self::assertFalse(icms_host_trust_path_is_public($this->trust, $this->root));
        self::assertFalse(icms_host_trust_path_is_public($this->root . '-trust', $this->root), 'a sibling with a common prefix');
        self::assertFalse(icms_host_trust_path_is_public('', $this->root));
    }

    private function writeMainfile(string $code): void
    {
        file_put_contents($this->root . '/mainfile.php', $code);
    }

    private function touch(string $file): void
    {
        (new Filesystem())->dumpFile($file, "<?php\n");
    }
}
