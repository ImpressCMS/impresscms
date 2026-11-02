<?php

declare(strict_types=1);

namespace App\Tests\Support;

/**
 * Detects whether the current code runs inside a PHPUnit child process
 * (#[RunTestsInSeparateProcesses] / #[RunInSeparateProcess]).
 */
final class Isolation
{
    public static function inChildProcess(): bool
    {
        // Declared only by PHPUnit's process-isolation template.
        return function_exists('__phpunit_run_isolated_test');
    }
}
