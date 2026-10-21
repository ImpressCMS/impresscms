<?php

declare(strict_types=1);

namespace App\Tests\Unit\Admin;

use App\Admin\LegacyRedirect;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

/**
 * Redirect targets stay on the site.
 */
#[Group('spec:admin-page-integration')]
final class LegacyRedirectTest extends TestCase
{
    private const SITE = 'https://www.example.test';

    /**
     * @return array<string, array{string, string}>
     */
    public static function targets(): array
    {
        return [
            'same site' => ['https://www.example.test/modules/system/admin.php?fct=mimetype', 'https://www.example.test/modules/system/admin.php?fct=mimetype'],
            'relative' => ['admin.php?fct=mimetype', 'admin.php?fct=mimetype'],
            'site root' => ['/', '/'],
            'external' => ['https://example.org/', self::SITE],
            'look-alike prefix' => ['https://www.example.test.evil.example/', self::SITE],
            'credentials' => ['https://www.example.test@evil.example/', self::SITE],
            'protocol-relative' => ['//evil.example/', self::SITE],
            'backslashes' => ['/\\evil.example/', self::SITE],
            'other port' => ['https://www.example.test:8443/', self::SITE],
            'javascript' => ['javascript:alert(1)', self::SITE],
            'history back' => ['javascript:history.go(-1)', self::SITE],
            'other scheme' => ['ftp://www.example.test/', self::SITE],
        ];
    }

    /**
     * @scenario External target
     */
    #[DataProvider('targets')]
    public function testTargetsOffTheSiteBecomeTheStartPage(string $target, string $expected): void
    {
        self::assertSame($expected, LegacyRedirect::sameSite($target, self::SITE));
    }
}
