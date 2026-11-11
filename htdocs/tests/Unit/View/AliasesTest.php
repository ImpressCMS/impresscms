<?php
/**
 * Verifies that every refactored Icms\View\* class retains its legacy
 * icms_view_* name so existing callers keep working.
 *
 * @package icms\tests
 */

declare(strict_types=1);

namespace Icms\Tests\Unit\View;

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
            'Block Entity'          => ['Icms\\View\\Block\\Entity', 'icms_view_block_Object'],
            'Block Position Entity' => ['Icms\\View\\Block\\Position\\Entity', 'icms_view_block_position_Object'],
            'Template File Entity'  => ['Icms\\View\\Template\\File\\Entity', 'icms_view_template_file_Object'],
            'Template Set Entity'   => ['Icms\\View\\Template\\Set\\Entity', 'icms_view_template_set_Object'],
            'Theme Entity'          => ['Icms\\View\\Theme\\Entity', 'icms_view_theme_Object'],
            'Theme Factory'         => ['Icms\\View\\Theme\\Factory', 'icms_view_theme_Factory'],
            'Breadcrumb'            => ['Icms\\View\\Breadcrumb', 'icms_view_Breadcrumb'],
            'PageBuilder'           => ['Icms\\View\\PageBuilder', 'icms_view_PageBuilder'],
            'PageNav'               => ['Icms\\View\\PageNav', 'icms_view_PageNav'],
            'Printerfriendly'       => ['Icms\\View\\Printerfriendly', 'icms_view_Printerfriendly'],
            'Tree'                  => ['Icms\\View\\Tree', 'icms_view_Tree'],
        ];
    }

    #[DataProvider('aliasProvider')]
    public function testLegacyAliasResolvesToModernClass(string $modern, string $legacy): void
    {
        self::assertLegacyAlias($modern, $legacy);
    }
}
