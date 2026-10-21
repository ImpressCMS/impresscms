<?php
/**
 * Verifies that every refactored Icms\Member\* entity retains its legacy
 * icms_member_* name so existing callers keep working.
 *
 * @package icms\tests
 */

declare(strict_types=1);

namespace Icms\Tests\Unit\Member;

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
            'Group Entity'            => ['Icms\\Member\\Group\\Entity', 'icms_member_group_Object'],
            'Group Membership Entity' => ['Icms\\Member\\Group\\Membership\\Entity', 'icms_member_group_membership_Object'],
            'Groupperm Entity'        => ['Icms\\Member\\Groupperm\\Entity', 'icms_member_groupperm_Object'],
            'User Entity'             => ['Icms\\Member\\User\\Entity', 'icms_member_user_Object'],
        ];
    }

    #[DataProvider('aliasProvider')]
    public function testLegacyAliasResolvesToModernClass(string $modern, string $legacy): void
    {
        self::assertLegacyAlias($modern, $legacy);
    }
}
