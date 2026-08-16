<?php
/**
 * Verifies that every refactored Icms\Data\* class retains its legacy
 * icms_data_* name so existing callers keep working.
 *
 * @package icms\tests
 */

declare(strict_types=1);

namespace Icms\Tests\Unit\Data;

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
            'Avatar\\Entity'        => ['Icms\\Data\\Avatar\\Entity', 'icms_data_avatar_Object'],
            'Avatar\\Handler'       => ['Icms\\Data\\Avatar\\Handler', 'icms_data_avatar_Handler'],
            'Comment\\Entity'       => ['Icms\\Data\\Comment\\Entity', 'icms_data_comment_Object'],
            'Comment\\Handler'      => ['Icms\\Data\\Comment\\Handler', 'icms_data_comment_Handler'],
            'Comment\\Renderer'     => ['Icms\\Data\\Comment\\Renderer', 'icms_data_comment_Renderer'],
            'File\\Entity'          => ['Icms\\Data\\File\\Entity', 'icms_data_file_Object'],
            'File\\Handler'         => ['Icms\\Data\\File\\Handler', 'icms_data_file_Handler'],
            'Notification\\Entity'  => ['Icms\\Data\\Notification\\Entity', 'icms_data_notification_Object'],
            'Notification\\Handler' => ['Icms\\Data\\Notification\\Handler', 'icms_data_notification_Handler'],
            'Page\\Entity'          => ['Icms\\Data\\Page\\Entity', 'icms_data_page_Object'],
            'Page\\Handler'         => ['Icms\\Data\\Page\\Handler', 'icms_data_page_Handler'],
            'Privmessage\\Entity'   => ['Icms\\Data\\Privmessage\\Entity', 'icms_data_privmessage_Object'],
            'Privmessage\\Handler'  => ['Icms\\Data\\Privmessage\\Handler', 'icms_data_privmessage_Handler'],
            'Urllink\\Entity'       => ['Icms\\Data\\Urllink\\Entity', 'icms_data_urllink_Object'],
            'Urllink\\Handler'      => ['Icms\\Data\\Urllink\\Handler', 'icms_data_urllink_Handler'],
        ];
    }

    #[DataProvider('aliasProvider')]
    public function testLegacyAliasResolvesToModernClass(string $modern, string $legacy): void
    {
        self::assertLegacyAlias($modern, $legacy);
    }
}
