<?php
/**
 * Verifies that every refactored Icms\Auth\* class retains its legacy
 * icms_auth_* name so existing callers keep working.
 *
 * @package icms\tests
 */

declare(strict_types=1);

namespace Icms\Tests\Unit\Auth;

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
            'Entity (legacy Object)' => ['Icms\\Auth\\Entity', 'icms_auth_Object'],
            'Factory'                => ['Icms\\Auth\\Factory', 'icms_auth_Factory'],
            'Xoops'                  => ['Icms\\Auth\\Xoops', 'icms_auth_Xoops'],
            'Ldap'                   => ['Icms\\Auth\\Ldap', 'icms_auth_Ldap'],
            'Ads'                    => ['Icms\\Auth\\Ads', 'icms_auth_Ads'],
            'Provisionning'          => ['Icms\\Auth\\Provisionning', 'icms_auth_Provisionning'],
        ];
    }

    #[DataProvider('aliasProvider')]
    public function testLegacyAliasResolvesToModernClass(string $modern, string $legacy): void
    {
        self::assertLegacyAlias($modern, $legacy);
    }
}
