<?php

declare(strict_types=1);

namespace App\Runtime;

/**
 * The PHP error settings as the web server started the request, captured in
 * host.php before Laravel's HandleExceptions bootstrapper sets
 * error_reporting(-1) and display_errors=Off. Legacy scripts run with these
 * settings, as they would without the host.
 */
final class PhpEnvironment
{
    private static ?int $errorReporting = null;

    private static string|false $displayErrors = false;

    public static function capture(): void
    {
        self::$errorReporting = error_reporting();
        self::$displayErrors = ini_get('display_errors');
    }

    public static function restore(): void
    {
        if (self::$errorReporting === null) {
            return;
        }

        error_reporting(self::$errorReporting);

        if (self::$displayErrors !== false) {
            ini_set('display_errors', self::$displayErrors);
        }
    }
}
