<?php

declare(strict_types=1);

namespace App\Tests\Integration\Kernel;

use App\Tests\Support\IsolatedLegacyTestCase;
use App\Tests\Support\TestSite;
use Icms\Kernel\Bootstrap;
use Icms\Kernel\NotInstalledException;
use Icms\Kernel\SiteClosedException;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
final class HeadlessBootstrapTest extends IsolatedLegacyTestCase
{
    private function headless(): Bootstrap
    {
        return new Bootstrap(Bootstrap::MODE_HEADLESS, TestSite::legacyRoot() . '/mainfile.php');
    }

    public function testHeadlessBootRunsEveryStageWithoutOutput(): void
    {
        $this->headless()->runAll();

        self::assertSame(Bootstrap::STAGES, Bootstrap::completedStages());
        self::assertSame(TestSite::SITE_NAME, $GLOBALS['icmsConfig']['sitename']);
        self::assertInstanceOf(\Icms\Core\Security::class, \icms::$security);
        self::assertSame(0, $GLOBALS['icmsConfig']['gzip_compression'], 'response encoding is left to the host');
    }

    public function testStagesRunInOrderAndOnlyOnce(): void
    {
        $bootstrap = $this->headless();
        $bootstrap->runTo('services');

        self::assertSame(['constants', 'autoload', 'services'], Bootstrap::completedStages());
        $security = \icms::$security;

        $bootstrap->run('services');
        $bootstrap->run('language');

        self::assertSame($security, \icms::$security, 'services are not booted twice');
        self::assertSame(['constants', 'autoload', 'services', 'output', 'language'], Bootstrap::completedStages());
    }

    public function testGlobalsDefinedByIncludedFilesStayGlobal(): void
    {
        $this->headless()->runTo('constants');

        self::assertSame('kfaenza', $GLOBALS['icms_images_setname'] ?? null, 'include/constants.php');
        self::assertArrayNotHasKey('nocommon', $GLOBALS['xoopsOption'] ?? [], 'nocommon applies to mainfile.php only');
    }

    public function testClosedSiteThrowsForAnonymousVisitors(): void
    {
        TestSite::setConfig('closesite', '1');
        try {
            $this->headless()->runTo('siteState');
            self::fail('Expected SiteClosedException.');
        } catch (SiteClosedException) {
            self::assertFalse(Bootstrap::isCompleted('siteState'));
            self::assertTrue(Bootstrap::isCompleted('themeSelection'));
        } finally {
            TestSite::setConfig('closesite', '0');
        }
    }

    public function testMissingInstallationThrows(): void
    {
        putenv('ICMS_MAINFILE');

        $this->expectException(NotInstalledException::class);
        $this->headless()->runTo('constants');
    }
}
