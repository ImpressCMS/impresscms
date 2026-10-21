<?php

declare(strict_types=1);

namespace App\Tests\Support;

use PDO;

/**
 * The scripted ImpressCMS test site: paths, fixture accounts, database
 * connection and snapshot/restore.
 *
 * The site lives in `var/test/` (mainfile.php and trust path) and in the
 * `icms_test` schema. `bin/test-db` creates it; tests restore the snapshot
 * that `bin/test-db` takes, so each test class starts from the same state.
 */
final class TestSite
{
    public const DB_PREFIX = 'icms';
    public const SITE_NAME = 'ImpressCMS Test Site';

    /** Fixture accounts (test-only credentials, no real secrets). */
    public const WEBMASTER_UNAME = 'webmaster';
    public const WEBMASTER_PASS = 'webmaster-test-pass';
    public const WEBMASTER_EMAIL = 'webmaster@example.test';
    public const MEMBER_UNAME = 'member';
    public const MEMBER_PASS = 'member-test-pass';
    public const MEMBER_EMAIL = 'member@example.test';
    /** Administrator of the system module with the `system_admin` right for user ranks only. */
    public const MEDIAADMIN_UNAME = 'mediaadmin';
    public const MEDIAADMIN_PASS = 'mediaadmin-test-pass';
    /** Administrator of the system module with the `system_admin` right for mimetypes. */
    public const MIMETYPEADMIN_UNAME = 'mimetypeadmin';
    public const MIMETYPEADMIN_PASS = 'mimetypeadmin-test-pass';

    /**
     * Fixture mimetypes, created after the installer's defaults; `use_extension`
     * lists the groups holding that permission.
     *
     * @var list<array{extension: string, types: string, name: string, dirname: list<string>, use_extension: list<int>}>
     */
    public const MIMETYPES = [
        ['extension' => 'icmsa', 'types' => 'application/x-icms-alpha', 'name' => 'ICMS Fixture Alpha', 'dirname' => ['system'], 'use_extension' => [1, 2]],
        ['extension' => 'icmsb', 'types' => 'application/x-icms-beta', 'name' => 'ICMS Fixture Beta', 'dirname' => ['system'], 'use_extension' => [1]],
        ['extension' => 'icmsc', 'types' => 'application/x-icms-gamma text/x-icms-gamma', 'name' => 'ICMS Fixture Gamma', 'dirname' => [], 'use_extension' => [1, 2, 3]],
    ];

    public static function repoRoot(): string
    {
        return str_replace('\\', '/', dirname(__DIR__, 2));
    }

    public static function legacyRoot(): string
    {
        return self::repoRoot() . '/htdocs';
    }

    public static function varDir(): string
    {
        return self::repoRoot() . '/var/test';
    }

    public static function mainfile(): string
    {
        return self::varDir() . '/mainfile.php';
    }

    public static function trustPath(): string
    {
        return self::varDir() . '/trust';
    }

    /**
     * "Install" the host in the test site's trust path: one-line stubs that
     * require the repository's host.php and vendor/autoload.php, so the web
     * root's index.php finds the host as on a real site.
     */
    public static function writeTrustPathHost(): void
    {
        $trust = self::trustPath();
        @mkdir($trust . '/vendor', 0777, true);
        file_put_contents($trust . '/host.php', "<?php\nrequire " . var_export(self::repoRoot() . '/host.php', true) . ";\n");
        file_put_contents($trust . '/vendor/autoload.php', "<?php\nreturn require " . var_export(self::repoRoot() . '/vendor/autoload.php', true) . ";\n");
    }

    /** Remove the host from the test site's trust path (vendor/ stays). */
    public static function removeTrustPathHost(): void
    {
        @unlink(self::trustPath() . '/host.php');
    }

    /**
     * $_SERVER entries a web server provides, for legacy code booted from the CLI.
     *
     * @return array<string, string>
     */
    public static function webServerDefaults(): array
    {
        return [
            'HTTP_HOST' => '127.0.0.1',
            'SERVER_NAME' => '127.0.0.1',
            'SERVER_PORT' => '80',
            'REQUEST_URI' => '/',
            'SCRIPT_NAME' => '/index.php',
            'PHP_SELF' => '/index.php',
            'QUERY_STRING' => '',
            'REMOTE_ADDR' => '127.0.0.1',
            'REQUEST_METHOD' => 'GET',
            'HTTP_USER_AGENT' => 'ImpressCMS test suite',
        ];
    }

    public static function isInstalled(): bool
    {
        return is_file(self::mainfile()) && is_file(self::varDir() . '/installed');
    }

    /**
     * @return array{host: string, port: int, user: string, pass: string, name: string}
     */
    public static function dbConfig(): array
    {
        return [
            'host' => self::env('ICMS_TEST_DB_HOST', '127.0.0.1'),
            'port' => (int) self::env('ICMS_TEST_DB_PORT', '3306'),
            'user' => self::env('ICMS_TEST_DB_USER', 'root'),
            'pass' => self::env('ICMS_TEST_DB_PASS', ''),
            'name' => self::env('ICMS_TEST_DB_NAME', 'icms_test'),
        ];
    }

    public static function snapshotSchema(): string
    {
        return self::dbConfig()['name'] . '_snapshot';
    }

    /**
     * A server-level connection (no default schema).
     */
    public static function serverConnection(): PDO
    {
        $c = self::dbConfig();

        return new PDO(
            sprintf('mysql:host=%s;port=%d;charset=utf8mb4', $c['host'], $c['port']),
            $c['user'],
            $c['pass'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],
        );
    }

    public static function connection(): PDO
    {
        $pdo = self::serverConnection();
        $pdo->exec('USE ' . self::quoteIdentifier(self::dbConfig()['name']));

        return $pdo;
    }

    public static function table(string $name): string
    {
        return self::DB_PREFIX . '_' . $name;
    }

    /**
     * Copy every table of the test schema into the snapshot schema.
     */
    public static function takeSnapshot(): void
    {
        self::copySchema(self::dbConfig()['name'], self::snapshotSchema());
    }

    /**
     * Reset the test schema to the snapshot taken by `bin/test-db`.
     */
    public static function restoreSnapshot(): void
    {
        self::copySchema(self::snapshotSchema(), self::dbConfig()['name']);
        self::clearLegacyCaches();
    }

    /**
     * Set one system configuration value (conf_modid 0).
     */
    public static function setConfig(string $name, string $value): void
    {
        $stmt = self::connection()->prepare(
            'UPDATE ' . self::table('config') . ' SET conf_value = ? WHERE conf_modid = 0 AND conf_name = ?'
        );
        $stmt->execute([$value, $name]);
        self::clearLegacyCaches();
    }

    /**
     * Remove compiled templates and cache files that depend on database state.
     */
    public static function clearLegacyCaches(): void
    {
        foreach (['templates_c', 'cache'] as $dir) {
            foreach (glob(self::legacyRoot() . '/' . $dir . '/*') ?: [] as $file) {
                if (is_file($file) && basename($file) !== 'index.html') {
                    @unlink($file);
                }
            }
        }
    }

    private static function copySchema(string $from, string $to): void
    {
        $pdo = self::serverConnection();
        $pdo->exec('CREATE DATABASE IF NOT EXISTS ' . self::quoteIdentifier($to) . ' DEFAULT CHARACTER SET utf8mb4');
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');

        foreach (self::tables($pdo, $to) as $table) {
            $pdo->exec('DROP TABLE ' . self::quoteIdentifier($to) . '.' . self::quoteIdentifier($table));
        }
        foreach (self::tables($pdo, $from) as $table) {
            $source = self::quoteIdentifier($from) . '.' . self::quoteIdentifier($table);
            $target = self::quoteIdentifier($to) . '.' . self::quoteIdentifier($table);
            $pdo->exec("CREATE TABLE {$target} LIKE {$source}");
            $pdo->exec("INSERT INTO {$target} SELECT * FROM {$source}");
        }

        $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    }

    /**
     * @return list<string>
     */
    private static function tables(PDO $pdo, string $schema): array
    {
        $stmt = $pdo->prepare(
            "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_TYPE = 'BASE TABLE'"
        );
        $stmt->execute([$schema]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function quoteIdentifier(string $name): string
    {
        return '`' . str_replace('`', '``', $name) . '`';
    }

    private static function env(string $name, string $default): string
    {
        $value = getenv($name);
        if ($value === false) {
            $value = $_ENV[$name] ?? $_SERVER[$name] ?? $default;
        }

        return (string) $value;
    }
}
