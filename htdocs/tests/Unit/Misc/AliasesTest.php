<?php
/**
 * Verifies the legacy names of classes that live directly under Icms\ or in
 * namespaces too small for their own test.
 *
 * @package icms\tests
 */

declare(strict_types=1);

namespace Icms\Tests\Unit\Misc;

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
            'Utils'             => ['Icms\\Utils', 'icms_Utils'],
            'Event'             => ['Icms\\Event', 'icms_Event'],
            'Config Handler'    => ['Icms\\Config\\Handler', 'icms_config_Handler'],
            'Autotasks ISystem' => ['Icms\\Sys\\Autotasks\\ISystem', 'icms_sys_autotasks_ISystem'],
            'Autotasks System'  => ['Icms\\Sys\\Autotasks\\System', 'icms_sys_autotasks_System'],
        ];
    }

    #[DataProvider('aliasProvider')]
    public function testLegacyAliasResolvesToModernClass(string $modern, string $legacy): void
    {
        self::assertLegacyAlias($modern, $legacy);
    }
}
