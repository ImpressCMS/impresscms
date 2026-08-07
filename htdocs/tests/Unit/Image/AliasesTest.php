<?php
/**
 * Verifies that every refactored Icms\Feeds\* class retains its legacy
 * icms_feeds_* name so existing callers keep working.
 *
 * @package icms\tests
 */

namespace Icms\Tests\Unit\Image;

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
            'Image'       => ['Icms\\Image\\Entity', 'icms_image_Object'],
            'Image Handler'       => ['Icms\\Image\\Handler', 'icms_image_Handler'],
            'Image Category Handler'       => ['Icms\\Image\\Category\\Handler', 'icms_image_category_Handler'],
            'Image Category Entity'       => ['Icms\\Image\\Category\\Entity', 'icms_image_category_Object'],
            'Image Set Handler'       => ['Icms\\Image\\Set\\Handler', 'icms_image_set_Handler'],
            'Image Set Entity'       => ['Icms\\Image\\Set\\Entity', 'icms_image_set_Object'],

        ];
    }

    #[DataProvider('aliasProvider')]
    public function testLegacyAliasResolvesToModernClass(string $modern, string $legacy): void
    {
        self::assertLegacyAlias($modern, $legacy);
    }
}