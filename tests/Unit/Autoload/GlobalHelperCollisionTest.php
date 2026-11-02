<?php

declare(strict_types=1);

namespace App\Tests\Unit\Autoload;

use App\Tests\Support\TestSite;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Composer loads the global helpers of Laravel (`app()`, `e()`, `url()`, …)
 * and of its dependencies on every request, guarded with function_exists().
 * Legacy declares its global functions without a guard, so a legacy function
 * with a helper's name would be a fatal "cannot redeclare" once the helper is
 * loaded first (design L3).
 */
#[Group('spec:shared-runtime')]
final class GlobalHelperCollisionTest extends TestCase
{
    public function testNoLegacyGlobalFunctionHasTheNameOfAComposerHelper(): void
    {
        $helpers = self::composerHelpers();
        self::assertGreaterThan(150, count($helpers), 'the helper files were found');

        $collisions = array_intersect_key(self::legacyGlobalFunctions(), $helpers);

        self::assertSame([], $collisions);
    }

    /**
     * @return array<string, true> lower-case helper names
     */
    private static function composerHelpers(): array
    {
        $vendor = TestSite::repoRoot() . '/vendor';
        $files = require "{$vendor}/composer/autoload_files.php";

        $names = [];
        foreach ($files as $file) {
            if (str_contains(str_replace('\\', '/', $file), '/htdocs/')) {
                continue;
            }

            preg_match_all("/function_exists\\(\\s*'([A-Za-z_][A-Za-z0-9_]*)'\\s*\\)/", (string) file_get_contents($file), $matches);
            foreach ($matches[1] as $name) {
                $names[strtolower($name)] = true;
            }
        }

        return $names;
    }

    /**
     * Functions declared outside any class, trait, interface or enum.
     *
     * @return array<string, string> lower-case name => file
     */
    private static function legacyGlobalFunctions(): array
    {
        $root = TestSite::legacyRoot();
        $functions = [];
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, RecursiveDirectoryIterator::SKIP_DOTS));
        foreach ($files as $file) {
            $path = str_replace('\\', '/', $file->getPathname());
            if ($file->getExtension() !== 'php' || str_contains($path, '/vendor/')) {
                continue;
            }

            foreach (self::globalFunctionsIn((string) file_get_contents($path)) as $name) {
                $functions[strtolower($name)] = substr($path, strlen(str_replace('\\', '/', $root)));
            }
        }

        return $functions;
    }

    /**
     * @return list<string>
     */
    private static function globalFunctionsIn(string $code): array
    {
        if (! str_contains($code, 'function')) {
            return [];
        }

        $tokens = token_get_all($code);
        $names = [];
        $depth = 0;
        $classDepths = [];
        $pendingClass = false;

        foreach ($tokens as $index => $token) {
            if (is_array($token) && in_array($token[0], [T_CLASS, T_TRAIT, T_INTERFACE, T_ENUM], true) && ! self::isClassConstant($tokens, $index)) {
                $pendingClass = true;
                continue;
            }

            if ($token === '{' || (is_array($token) && in_array($token[0], [T_CURLY_OPEN, T_DOLLAR_OPEN_CURLY_BRACES], true))) {
                $depth++;
                if ($pendingClass) {
                    $classDepths[] = $depth;
                    $pendingClass = false;
                }
                continue;
            }

            if ($token === '}') {
                if ($classDepths !== [] && end($classDepths) === $depth) {
                    array_pop($classDepths);
                }
                $depth--;
                continue;
            }

            if (! is_array($token) || $token[0] !== T_FUNCTION || $classDepths !== []) {
                continue;
            }

            $name = self::nextName($tokens, $index);
            if ($name !== null) {
                $names[] = $name;
            }
        }

        return $names;
    }

    /**
     * @param array<int, mixed> $tokens
     */
    private static function isClassConstant(array $tokens, int $index): bool
    {
        for ($i = $index - 1; $i >= 0; $i--) {
            if (is_array($tokens[$i]) && $tokens[$i][0] === T_WHITESPACE) {
                continue;
            }

            return is_array($tokens[$i]) && $tokens[$i][0] === T_DOUBLE_COLON;
        }

        return false;
    }

    /**
     * The name of a function declaration; null for closures.
     *
     * @param array<int, mixed> $tokens
     */
    private static function nextName(array $tokens, int $index): ?string
    {
        for ($i = $index + 1, $count = count($tokens); $i < $count; $i++) {
            $token = $tokens[$i];
            if (is_array($token) && $token[0] === T_WHITESPACE) {
                continue;
            }

            if ($token === '&') {
                continue;
            }

            return is_array($token) && $token[0] === T_STRING ? $token[1] : null;
        }

        return null;
    }
}
