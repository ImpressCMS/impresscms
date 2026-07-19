<?php
/**
 * Verifies that every refactored Icms\Plugins\* class retains its legacy
 * icms_plugins_* name so existing callers keep working.
 *
 * @package icms\tests
 */

declare(strict_types=1);

namespace Icms\Tests\Unit\Plugins;

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
            'Entity (legacy Object)' => ['Icms\\Plugins\\Entity', 'icms_plugins_Object'],
            'EditorHandler'          => ['Icms\\Plugins\\EditorHandler', 'icms_plugins_EditorHandler'],
            'Handler'                => ['Icms\\Plugins\\Handler', 'icms_plugins_Handler'],
        ];
    }

    #[DataProvider('aliasProvider')]
    public function testLegacyAliasResolvesToModernClass(string $modern, string $legacy): void
    {
        self::assertLegacyAlias($modern, $legacy);
    }
}
