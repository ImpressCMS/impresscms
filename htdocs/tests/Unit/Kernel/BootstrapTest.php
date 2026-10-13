<?php
/**
 * Stage list and argument checks of Icms\Kernel\Bootstrap. Running stages
 * boots ImpressCMS and is covered by the root Integration suite.
 *
 * @package icms\tests
 */

declare(strict_types=1);

namespace Icms\Tests\Unit\Kernel;

use Icms\Kernel\Bootstrap;
use Icms\Kernel\NotInstalledException;
use Icms\Kernel\SiteClosedException;
use PHPUnit\Framework\TestCase;

final class BootstrapTest extends TestCase
{
    public function testStagesFollowTheFormerCommonPhpOrder(): void
    {
        self::assertSame(
            ['constants', 'autoload', 'services', 'output', 'language', 'themeSelection', 'siteState', 'module', 'online'],
            Bootstrap::STAGES,
        );
    }

    public function testUnknownModeIsRejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Bootstrap('quiet');
    }

    public function testUnknownStageIsRejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        (new Bootstrap(Bootstrap::MODE_HEADLESS))->run('theme');
    }

    public function testExceptionsAreRuntimeExceptions(): void
    {
        self::assertInstanceOf(\RuntimeException::class, new SiteClosedException());
        self::assertInstanceOf(\RuntimeException::class, new NotInstalledException());
    }
}
