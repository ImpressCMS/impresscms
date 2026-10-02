<?php
/**
 * Static checks on libraries/Icms/aliases.php, the single source of legacy
 * icms_* to Icms\* class name mappings.
 *
 * @package icms\tests
 */

declare(strict_types=1);

namespace Icms\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class AliasMapTest extends TestCase
{
    private const LIBRARY_DIR = __DIR__ . '/../../libraries/Icms';

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function aliasProvider(): array
    {
        $cases = [];

        foreach (require self::LIBRARY_DIR . '/aliases.php' as $legacy => $modern) {
            $cases[$legacy] = [$legacy, $modern];
        }

        return $cases;
    }

    #[DataProvider('aliasProvider')]
    public function testModernClassIsDeclaredInItsPsr4File(string $legacy, string $modern): void
    {
        $file = self::LIBRARY_DIR . '/' . str_replace('\\', '/', substr($modern, strlen('Icms\\'))) . '.php';

        self::assertFileExists($file, "{$legacy} points at {$modern}");

        $shortName = substr($modern, (int) strrpos($modern, '\\') + 1);
        self::assertMatchesRegularExpression(
            '/^\s*(?:abstract\s+|final\s+)?(?:class|interface|trait)\s+' . $shortName . '\b/mi',
            (string) file_get_contents($file)
        );
    }

    public function testLegacyNamesAreUniqueIgnoringCase(): void
    {
        $names = array_map('strtolower', array_keys(require self::LIBRARY_DIR . '/aliases.php'));

        self::assertSame(array_unique($names), $names);
    }

    public function testNoClassFileRegistersItsOwnAlias(): void
    {
        $offenders = [];
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(self::LIBRARY_DIR));

        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            if (str_contains((string) file_get_contents($file->getPathname()), 'class_alias(')) {
                $offenders[] = $file->getPathname();
            }
        }

        self::assertSame([], $offenders);
    }
}
