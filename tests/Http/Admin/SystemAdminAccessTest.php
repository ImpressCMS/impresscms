<?php

declare(strict_types=1);

namespace App\Tests\Http\Admin;

use App\Tests\Support\HttpTestCase;
use App\Tests\Support\TestSite;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;

/**
 * The access rules of system admin functions for the identities of the test
 * site (App\Admin\SystemAdminFunctionVoter): webmasters and category admins
 * may use the mimetype administration, other system administrators and
 * visitors may not.
 */
#[Group('spec:admin-page-integration')]
final class SystemAdminAccessTest extends HttpTestCase
{
    /**
     * @return array<string, array{?string, ?string, string, string}>
     */
    public static function identities(): array
    {
        return [
            'webmaster' => [TestSite::WEBMASTER_UNAME, TestSite::WEBMASTER_PASS, 'mimetype', 'granted'],
            'category admin' => [TestSite::MIMETYPEADMIN_UNAME, TestSite::MIMETYPEADMIN_PASS, 'mimetype', 'granted'],
            'category admin, other function' => [TestSite::MIMETYPEADMIN_UNAME, TestSite::MIMETYPEADMIN_PASS, 'userrank', 'denied'],
            'admin without the category' => [TestSite::MEDIAADMIN_UNAME, TestSite::MEDIAADMIN_PASS, 'mimetype', 'denied'],
            'admin with another category' => [TestSite::MEDIAADMIN_UNAME, TestSite::MEDIAADMIN_PASS, 'userrank', 'granted'],
            'member' => [TestSite::MEMBER_UNAME, TestSite::MEMBER_PASS, 'mimetype', 'denied'],
            'anonymous' => [null, null, 'mimetype', 'denied'],
            'unknown function' => [TestSite::WEBMASTER_UNAME, TestSite::WEBMASTER_PASS, 'nosuchfunction', 'denied'],
        ];
    }

    /**
     * @scenario Webmaster
     * @scenario Administrator without the category right
     * @scenario Anonymous visitor
     */
    #[DataProvider('identities')]
    public function testVoterAppliesTheLegacyRules(?string $uname, ?string $pass, string $fct, string $expected): void
    {
        if ($uname !== null && $pass !== null) {
            $this->loginAs($uname, $pass);
        }

        $response = $this->request('GET', '/_test/system-admin/' . $fct);

        self::assertSame(200, $response->getStatusCode(), self::serverLog());
        self::assertSame($expected, $response->getContent());
    }
}
