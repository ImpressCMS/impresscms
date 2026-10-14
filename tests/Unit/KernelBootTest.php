<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Kernel;
use App\Tests\Support\TestSite;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * The container compiles from scratch without loading legacy class files:
 * those exit unless ImpressCMS constants are defined, which would end the
 * request that happens to rebuild the container (in debug mode, any request).
 */
final class KernelBootTest extends TestCase
{
    /**
     * @return array<string, array{bool}>
     */
    public static function modes(): array
    {
        return ['debug' => [true], 'production' => [false]];
    }

    #[DataProvider('modes')]
    #[RunInSeparateProcess]
    public function testContainerCompilesWithoutLoadingLegacyClasses(bool $debug): void
    {
        define('ICMS_HOST_AUTOLOADED', true);
        (new Filesystem())->remove(TestSite::repoRoot() . '/var/cache/test');
        $before = self::legacyClasses();

        $kernel = new Kernel('test', $debug);
        $kernel->boot();

        self::assertTrue($kernel->getContainer()->has('icms.handlers'));
        self::assertSame($before, self::legacyClasses(), 'no Icms\\ class with an exit guard was loaded');
        $kernel->shutdown();
    }

    /**
     * @return list<string>
     */
    private static function legacyClasses(): array
    {
        $classes = array_filter(
            array_merge(get_declared_classes(), get_declared_interfaces()),
            static fn (string $class): bool => str_starts_with($class, 'Icms\\')
                // guard-free interfaces the compiler may reflect for error messages
                && !in_array($class, ['Icms\\Db\\IConnection'], true),
        );
        sort($classes);

        return array_values($classes);
    }
}
