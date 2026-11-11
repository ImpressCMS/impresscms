<?php
/**
 * Verifies that every refactored Icms\Feeds\* class retains its legacy
 * icms_feeds_* name so existing callers keep working.
 *
 * @package icms\tests
 */

declare(strict_types=1);

namespace File;

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
            'DownloadHandler'       => ['Icms\\File\\DownloadHandler', 'icms_file_DownloadHandler'],
            'MediaUploadHandler'       => ['Icms\\File\\MediaUploadHandler', 'icms_file_MediaUploadHandler'],
            'TarDownloader'       => ['Icms\\File\\TarDownloader', 'icms_file_TarDownloader'],
            'TarFileHandler'       => ['Icms\\File\\TarFileHandler', 'icms_file_TarFileHandler'],
            'ZipDownloader'       => ['Icms\\File\\ZipDownloader', 'icms_file_ZipDownloader'],
            'ZipFileHandler'       => ['Icms\\File\\ZipFileHandler', 'icms_file_ZipFileHandler'],

        ];
    }

    #[DataProvider('aliasProvider')]
    public function testLegacyAliasResolvesToModernClass(string $modern, string $legacy): void
    {
        self::assertLegacyAlias($modern, $legacy);
    }
}
