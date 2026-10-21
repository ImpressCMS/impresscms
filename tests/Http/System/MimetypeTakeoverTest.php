<?php

declare(strict_types=1);

namespace App\Tests\Http\System;

use App\EventListener\Test\HostMarkerListener;
use App\Tests\Support\HttpTestCase;
use App\Tests\Support\ServerProcess;
use App\Tests\Support\TestSite;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\BrowserKit\Response;

/**
 * The takeover of the mimetype administration, beyond snapshot parity.
 *
 * What changed on purpose: the framework page validates the ImpressCMS
 * security token on saving and deleting (design D9), which legacy accepted
 * without one, and it validates the submitted values by the mimetype's
 * metadata. Requests without a valid token store and delete nothing.
 */
#[Group('spec:system-mimetype-admin')]
final class MimetypeTakeoverTest extends HttpTestCase
{
    use MimetypePage;

    /** _CORE_TOKENINVALID */
    private const TOKEN_INVALID = 'No valid token found in request/session';

    protected function setUp(): void
    {
        // admin.php only reaches the host when every .php request does
        if (ServerProcess::routing() !== 3) {
            self::markTestSkipped('The mimetype takeover needs routing level 3 (ICMS_TEST_ROUTING=3).');
        }
        TestSite::restoreSnapshot();
        parent::setUp();
    }

    /**
     * @scenario Save without a token
     */
    public function testSaveWithoutATokenStoresNothing(): void
    {
        $response = $this->asWebmaster()->request('POST', self::PAGE . '&op=mod', [
            'op' => 'addmimetype',
            'mimetypeid' => '0',
            'extension' => 'icmsd',
            'types' => 'application/x-icms-delta',
            'name' => 'ICMS Fixture Delta',
            'dirname' => ['system'],
            'use_extension' => ['1'],
        ], $this->referer());

        self::assertNull(self::mimetypeRow('icmsd'));
        $this->assertRedirectReports($response, self::TOKEN_INVALID);
    }

    /**
     * @scenario Replayed or missing token
     */
    public function testReplayedTokenDeletesNothing(): void
    {
        $this->asWebmaster();
        [$crawler] = $this->openDeleteConfirmation(self::BETA);
        $values = $crawler->filterXPath('//div[@class="confirmMsg"]//form')->form()->getPhpValues();
        self::assertNotEmpty($values['XOOPS_TOKEN_REQUEST'] ?? null, 'The confirmation carries no token.');

        $this->request('POST', '/modules/system/admin.php', $values, $this->referer());
        self::assertNull(self::mimetypeRow('icmsb'), 'The first, valid confirmation did not delete.');

        // the same token again, for another mimetype
        $response = $this->request('POST', '/modules/system/admin.php', ['mimetypeid' => (string) self::ALPHA] + $values, $this->referer());
        self::assertNotNull(self::mimetypeRow('icmsa'));
        self::assertSame([1, 2], self::useExtensionGroups(self::ALPHA));
        $this->assertRedirectReports($response, self::TOKEN_INVALID);
    }

    /**
     * @scenario Replayed or missing token
     */
    public function testConfirmationWithoutATokenDeletesNothing(): void
    {
        $response = $this->asWebmaster()->request('POST', '/modules/system/admin.php', [
            'op' => 'del',
            'mimetypeid' => (string) self::ALPHA,
            'confirm' => '1',
            'redirect_page' => static::url(self::PAGE),
            'fct' => 'mimetype',
        ], $this->referer());

        self::assertNotNull(self::mimetypeRow('icmsa'));
        self::assertSame([1, 2], self::useExtensionGroups(self::ALPHA));
        $this->assertRedirectReports($response, self::TOKEN_INVALID);
    }

    /**
     * @scenario Required field left empty
     */
    #[Group('spec:ipf-forms')]
    public function testEmptyRequiredFieldIsRejectedNamingItsCaption(): void
    {
        $response = $this->asWebmaster()->submitMimetypeForm(self::PAGE . '&op=mod', [
            'extension' => '',
            'types' => 'application/x-icms-delta',
            'name' => 'ICMS Fixture Delta',
            'dirname' => ['system'],
        ]);

        self::assertCount(3, self::fixtureRows());
        self::assertNull(self::mimetypeRow(''));
        $this->assertRedirectReports($response, 'File Extension is required');
    }

    /**
     * @scenario Other admin function
     */
    public function testOtherAdminFunctionsStayLegacy(): void
    {
        $response = $this->asWebmaster()->request('GET', '/modules/system/admin.php?fct=userrank');

        self::assertSame(200, $response->getStatusCode(), self::serverLog());
        self::assertSame('icms_legacy', $response->getHeader(HostMarkerListener::ROUTE_HEADER));

        $mimetypes = $this->request('GET', self::PAGE);
        self::assertSame(self::ROUTE, $mimetypes->getHeader(HostMarkerListener::ROUTE_HEADER));
    }

    /**
     * Custom redirection (the test site's default): a 302, and the next page
     * shows the message.
     */
    private function assertRedirectReports(Response $response, string $message): void
    {
        self::assertSame(302, $response->getStatusCode(), self::serverLog());
        $next = $this->request('GET', (string) $response->getHeader('Location'));
        self::assertStringContainsString($message, $next->getContent());
    }
}
