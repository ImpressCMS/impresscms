<?php
/**
 * Trait with reusable assertions that verify legacy icms_* class names remain
 * accessible after refactoring classes into the Icms\ PSR-4 namespace.
 *
 * @package icms\tests
 */

declare(strict_types=1);

namespace Icms\Tests\Support;

use PHPUnit\Framework\Assert;

trait LegacyAliasAssertions
{
    /**
     * Assert that $legacy resolves to the same class as $modern and that
     * instances of $modern are recognised as instances of $legacy.
     */
    protected static function assertLegacyAlias(string $modern, string $legacy): void
    {
        Assert::assertTrue(
            class_exists($modern, true) || interface_exists($modern, true) || trait_exists($modern, true),
            sprintf('Modern type "%s" is expected to be loadable.', $modern)
        );
        Assert::assertTrue(
            class_exists($legacy, true) || interface_exists($legacy, true) || trait_exists($legacy, true),
            sprintf('Legacy type "%s" is expected to be loadable.', $legacy)
        );

        if (class_exists($modern, false) && class_exists($legacy, false)) {
            $modernReflect = new \ReflectionClass($modern);
            $legacyReflect = new \ReflectionClass($legacy);
            Assert::assertSame(
                $modernReflect->getName(),
                $legacyReflect->getName(),
                sprintf(
                    'Legacy class "%s" should resolve to the same class as "%s" (got "%s").',
                    $legacy,
                    $modern,
                    $legacyReflect->getName()
                )
            );
        }
    }
}
