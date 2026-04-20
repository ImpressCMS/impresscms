<?php
/**
 * Verifies that every refactored Icms\Feeds\* class retains its legacy
 * icms_feeds_* name so existing callers keep working.
 *
 * @package icms\tests
 */

declare(strict_types=1);

namespace Icms\Tests\Unit\Feeds;

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
            'Rss'       => ['Icms\\Feeds\\Rss', 'icms_feeds_Rss'],
            'Simplerss' => ['Icms\\Feeds\\Simplerss', 'icms_feeds_Simplerss'],
        ];
    }

    #[DataProvider('aliasProvider')]
    public function testLegacyAliasResolvesToModernClass(string $modern, string $legacy): void
    {
        self::assertLegacyAlias($modern, $legacy);
    }
}
