<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Tests\Support\IsolatedLegacyTestCase;
use App\Tests\Support\TestSite;
use Composer\InstalledVersions;
use Icms\Kernel\Bootstrap;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
#[Group('spec:shared-runtime')]
final class SharedAutoloaderTest extends IsolatedLegacyTestCase
{
    /**
     * @scenario Shared library version
     */
    public function testLegacyBootUnderTheHostLoadsNoSecondVendorTree(): void
    {
        (new Bootstrap(Bootstrap::MODE_HEADLESS, TestSite::legacyRoot() . '/mainfile.php'))->runAll();

        // a Symfony component used by the host and by legacy code (composer/composer)
        $console = new \ReflectionClass(\Symfony\Component\Console\Application::class);
        $rootVendor = TestSite::repoRoot() . '/vendor/';
        self::assertStringStartsWith(strtolower($rootVendor), strtolower(str_replace('\\', '/', (string) $console->getFileName())));
        self::assertStringStartsWith('7.4.', (string) InstalledVersions::getVersion('symfony/console'));

        foreach (get_included_files() as $file) {
            $file = strtolower(str_replace('\\', '/', $file));
            self::assertStringNotContainsString('/htdocs/vendor/', $file);
            self::assertStringNotContainsString('/trust/vendor/', $file);
        }
    }
}
