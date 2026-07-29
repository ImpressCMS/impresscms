<?php
/**
 * Verifies that every refactored Icms\Feeds\* class retains its legacy
 * icms_feeds_* name so existing callers keep working.
 *
 * @package icms\tests
 */

declare(strict_types=1);

namespace Form;

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
            'Form Base'       => ['Icms\\Form\\Base', 'icms_form_Base'],
            'Form Element'       => ['Icms\\Form\\Element', 'icms_form_Element'],
            'Form Group Permission'       => ['Icms\\Form\\GroupPermission', 'icms_form_GroupPermission'],
            'Simple Form'       => ['Icms\\Form\\Simple', 'icms_form_Simple'],
            'Table Form'       => ['Icms\\Form\\Table', 'icms_form_Table'],
            'Theme Form'       => ['Icms\\Form\\Theme', 'icms_form_Theme'],


        ];
    }

    #[DataProvider('aliasProvider')]
    public function testLegacyAliasResolvesToModernClass(string $modern, string $legacy): void
    {
        self::assertLegacyAlias($modern, $legacy);
    }
}
