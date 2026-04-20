<?php
/**
 * Verifies Icms\Core\Debug remains accessible under its legacy icms_core_Debug
 * name and that its public surface has not regressed during the PSR-4 refactor.
 *
 * @package icms\tests
 */

declare(strict_types=1);

namespace Icms\Tests\Unit\Core;

use Icms\Core\Debug;
use Icms\Tests\Support\LegacyAliasAssertions;
use PHPUnit\Framework\TestCase;

final class DebugTest extends TestCase
{
    use LegacyAliasAssertions;

    public function testLegacyAliasResolvesToModernClass(): void
    {
        self::assertLegacyAlias(Debug::class, 'icms_core_Debug');
    }

    public function testPublicStaticSurface(): void
    {
        $reflection = new \ReflectionClass(Debug::class);
        foreach (['message', 'vardump', 'setDeprecated'] as $method) {
            self::assertTrue(
                $reflection->hasMethod($method),
                sprintf('Icms\\Core\\Debug should expose static method %s().', $method)
            );
            $m = $reflection->getMethod($method);
            self::assertTrue($m->isPublic(), "{$method} must be public");
            self::assertTrue($m->isStatic(), "{$method} must be static");
        }
    }

    public function testMessageRendersExpectedMarkup(): void
    {
        if (!defined('_CORE_DEBUG')) {
            define('_CORE_DEBUG', 'Debug');
        }

        ob_start();
        Debug::message('unit-test-marker');
        $output = (string) ob_get_clean();

        self::assertStringContainsString('unit-test-marker', $output);
        self::assertStringContainsString(_CORE_DEBUG, $output);
    }
}
