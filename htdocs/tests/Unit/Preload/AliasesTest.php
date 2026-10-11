<?php
/**
 * Verifies that every refactored Icms\Preload\* class retains its legacy
 * icms_preload_* name so existing callers keep working.
 *
 * @package icms\tests
 */

declare(strict_types=1);

namespace Icms\Tests\Unit\Preload;

use Icms\Tests\Support\LegacyAliasAssertions;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class AliasesTest extends TestCase
{
    use LegacyAliasAssertions;

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function aliasProvider(): array
    {
        return [
            'Item'             => ['Icms\\Preload\\Item', 'icms_preload_Item'],
            'Handler'          => ['Icms\\Preload\\Handler', 'icms_preload_Handler'],
            'LibrariesHandler' => ['Icms\\Preload\\LibrariesHandler', 'icms_preload_LibrariesHandler'],
        ];
    }

    #[DataProvider('aliasProvider')]
    public function testLegacyAliasResolvesToModernClass(string $modern, string $legacy): void
    {
        self::assertLegacyAlias($modern, $legacy);
    }
}
