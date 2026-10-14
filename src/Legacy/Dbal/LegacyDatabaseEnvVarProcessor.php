<?php

declare(strict_types=1);

namespace App\Legacy\Dbal;

use App\Legacy\LegacyRuntime;
use Symfony\Component\DependencyInjection\EnvVarProcessorInterface;
use Symfony\Component\DependencyInjection\Exception\EnvNotFoundException;

/**
 * `%env(icms_db:XOOPS_DB_HOST)%`: the database settings of mainfile.php, read
 * after the `constants` stage, so that DBAL needs no second configuration.
 * Resolved only when the DBAL connection is created.
 */
final class LegacyDatabaseEnvVarProcessor implements EnvVarProcessorInterface
{
    private const CONSTANTS = [
        'XOOPS_DB_HOST' => '',
        'XOOPS_DB_USER' => '',
        'XOOPS_DB_PASS' => '',
        'XOOPS_DB_NAME' => '',
        'XOOPS_DB_CHARSET' => 'utf8mb4',
        'XOOPS_DB_PCONNECT' => '0',
        'ICMS_DB_PORT' => '3306',
    ];

    public function __construct(private readonly LegacyRuntime $runtime)
    {
    }

    public function getEnv(string $prefix, string $name, \Closure $getEnv): string
    {
        if (!array_key_exists($name, self::CONSTANTS)) {
            throw new EnvNotFoundException(sprintf('"%s" is not an ImpressCMS database setting.', $name));
        }
        $this->runtime->bootTo('constants');

        $value = defined($name) ? (string) constant($name) : '';

        return $value !== '' ? $value : self::CONSTANTS[$name];
    }

    public static function getProvidedTypes(): array
    {
        return ['icms_db' => 'string'];
    }
}
