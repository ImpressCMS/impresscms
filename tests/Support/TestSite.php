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
