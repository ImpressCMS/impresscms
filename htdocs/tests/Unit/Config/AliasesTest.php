<?php
/**
 * Verifies that every refactored Icms\Config\* class retains its legacy
 * icms_config_* name so existing callers keep working.
 *
 * @package icms\tests
 */

declare(strict_types=1);

namespace Config;

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
            'Config Category Object' => ['Icms\\Config\\Category\\Entity', 'icms_config_category_Object'],
            'Config Category Handler' => ['Icms\\Config\\Category\\Handler', 'icms_config_category_Handler'],
            'Config Item Object' => ['Icms\\Config\\Item\\Entity', 'icms_config_item_Object'],
            'Config Item Handler' => ['Icms\\Config\\Item\\Handler', 'icms_config_item_Handler'],
            'Config Option Object' => ['Icms\\Config\\Option\\Entity', 'icms_config_option_Object'],
            'Config Option Handler' => ['Icms\\Config\\Option\\Handler', 'icms_config_option_Handler'],
        ];
    }

    #[DataProvider('aliasProvider')]
    public function testLegacyAliasResolvesToModernClass(string $modern, string $legacy): void
    {
        self::assertLegacyAlias($modern, $legacy);
    }
}
