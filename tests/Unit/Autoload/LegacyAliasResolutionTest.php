<?php

declare(strict_types=1);

namespace App\Tests\Unit\Autoload;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\TestCase;

/**
 * Legacy icms_* names and PSR-4 Icms\ names resolve to the same class under
 * the root (host) autoloader, whichever is referenced first. Each test runs
 * in a fresh process so that nothing is loaded beforehand.
 */
#[Group('spec:shared-runtime')]
final class LegacyAliasResolutionTest extends TestCase
{
    protected function setUp(): void
    {
        // class files guard on ICMS_ROOT_PATH
        require_once dirname(__DIR__, 3) . '/htdocs/tests/environment.php';
    }

    /**
     * @scenario Legacy name first
     */
    #[RunInSeparateProcess]
    public function testLegacyNameReferencedFirst(): void
    {
        self::assertFalse(class_exists(\Icms\Ipf\Handler::class, false));

        self::assertTrue(class_exists('icms_ipf_Handler'));
        self::assertTrue(class_exists(\Icms\Ipf\Handler::class, false), 'loading the legacy name loads the modern class');
        self::assertSame(\Icms\Ipf\Handler::class, (new \ReflectionClass('icms_ipf_Handler'))->getName());

        $handler = (new \ReflectionClass('icms_ipf_Handler'))->newInstanceWithoutConstructor();
        self::assertInstanceOf('icms_ipf_Handler', $handler);
        self::assertInstanceOf(\Icms\Ipf\Handler::class, $handler);
        self::assertSame(1, self::timesIncluded('libraries/Icms/Ipf/Handler.php'));
    }

    /**
     * @scenario Modern name first under the host
     */
    #[RunInSeparateProcess]
    public function testModernNameReferencedFirst(): void
    {
        self::assertFalse(class_exists('icms_core_Session', false));

        self::assertTrue(class_exists(\Icms\Core\Session::class));
        self::assertTrue(class_exists('icms_core_Session'));
        self::assertSame(\Icms\Core\Session::class, (new \ReflectionClass('icms_core_Session'))->getName());
        self::assertSame(1, self::timesIncluded('libraries/Icms/Core/Session.php'));
    }

    private static function timesIncluded(string $suffix): int
    {
        $suffix = strtolower($suffix);

        return count(array_filter(
            get_included_files(),
            static fn (string $file): bool => str_ends_with(strtolower(str_replace('\\', '/', $file)), $suffix),
        ));
    }
}
