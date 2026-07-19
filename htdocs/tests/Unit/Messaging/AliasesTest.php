<?php
/**
 * Verifies that every refactored Icms\Messaging\* class retains its legacy
 * icms_messaging_* name so existing callers keep working.
 *
 * @package icms\tests
 */

declare(strict_types=1);

namespace Icms\Tests\Unit\Messaging;

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
            'Handler'      => ['Icms\\Messaging\\Handler', 'icms_messaging_Handler'],
            'EmailHandler' => ['Icms\\Messaging\\EmailHandler', 'icms_messaging_EmailHandler'],
        ];
    }

    #[DataProvider('aliasProvider')]
    public function testLegacyAliasResolvesToModernClass(string $modern, string $legacy): void
    {
        self::assertLegacyAlias($modern, $legacy);
    }
}
