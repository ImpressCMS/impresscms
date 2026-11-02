<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\Support\LegacyAppTestCase;
use App\Tests\Support\TestSite;
use Icms\Kernel\Bootstrap;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
#[Group('spec:shared-runtime')]
final class HeadlessRuntimeTest extends LegacyAppTestCase
{
    /**
     * @scenario Route without legacy needs
     */
    public function testHealthRouteBootsNothing(): void
    {
        $response = $this->get('/up');

        self::assertSame('ok', $response->getContent());
        self::assertSame([], Bootstrap::completedStages());
        self::assertFalse(class_exists('icms', false), 'the icms facade was never loaded');
        self::assertFalse(
            class_exists(\Icms\Db\Factory::class, false),
            'no database connection: the connection factory was never loaded',
        );
    }

    /**
     * @scenario New route uses legacy configuration
     */
    public function testRouteReadsTheConfiguredSiteNameWithoutThemeOutput(): void
    {
        $response = $this->get('/_test/legacy/sitename');

        $response->assertOk();
        self::assertSame(TestSite::SITE_NAME, $response->getContent(), 'nothing but the site name: no theme output');
        self::assertTrue(Bootstrap::isCompleted('siteState'));
        self::assertFalse(Bootstrap::isCompleted('module'), 'booted only as far as the route asked');
    }

    /**
     * @scenario Site closed
     */
    public function testClosedSiteIs503AndTheProcessGoesOn(): void
    {
        TestSite::setConfig('closesite', '1');

        try {
            $response = $this->get('/_test/legacy/sitename');
        } finally {
            TestSite::setConfig('closesite', '0');
        }

        $response->assertStatus(503);
        self::assertStringNotContainsString(TestSite::SITE_NAME, (string) $response->getContent());
        // still running: legacy did not exit()
        self::assertFalse(Bootstrap::isCompleted('siteState'));
    }
}
