<?php
/**
 * Verifies that every refactored Icms\Core\* class/interface retains its
 * legacy icms_core_* name so existing callers keep working.
 *
 * @package icms\tests
 */

declare(strict_types=1);

namespace Icms\Tests\Unit\Core;

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
            'Debug'                   => ['Icms\\Core\\Debug', 'icms_core_Debug'],
            'Message'                 => ['Icms\\Core\\Message', 'icms_core_Message'],
            'VersioncheckerInterface' => ['Icms\\Core\\VersioncheckerInterface', 'icms_core_VersioncheckerInterface'],
            'Entity Handler'           => ['Icms\\Core\\EntityHandler', 'icms_core_ObjectHandler'],
            'Versionchecker'          => ['Icms\\Core\\Versionchecker', 'icms_core_Versionchecker'],
            'Versioncheckergithub'    => ['Icms\\Core\\Versioncheckergithub', 'icms_core_Versioncheckergithub'],
            'Versionchecker_RSS'      => ['Icms\\Core\\Versionchecker_RSS', 'icms_core_Versionchecker_RSS'],
            'Logger'                  => ['Icms\\Core\\Logger', 'icms_core_Logger'],
            'StopSpammer'             => ['Icms\\Core\\StopSpammer', 'icms_core_StopSpammer'],
            'HTMLFilter'              => ['Icms\\Core\\HTMLFilter', 'icms_core_HTMLFilter'],
            'OnlineHandler'           => ['Icms\\Core\\OnlineHandler', 'icms_core_OnlineHandler'],
            'Password'                => ['Icms\\Core\\Password', 'icms_core_Password'],
            'Security'                => ['Icms\\Core\\Security', 'icms_core_Security'],
            'Entity (legacy Object)'  => ['Icms\\Core\\Entity', 'icms_core_Object'],
            'Session'                 => ['Icms\\Core\\Session', 'icms_core_Session'],
            'DataFilter'              => ['Icms\\Core\\DataFilter', 'icms_core_DataFilter'],
            'Textsanitizer'           => ['Icms\\Core\\Textsanitizer', 'icms_core_Textsanitizer'],
            'Filesystem'              => ['Icms\\Core\\Filesystem', 'icms_core_Filesystem'],
        ];
    }

    #[DataProvider('aliasProvider')]
    public function testLegacyAliasResolvesToModernClass(string $modern, string $legacy): void
    {
        self::assertLegacyAlias($modern, $legacy);
    }
}
