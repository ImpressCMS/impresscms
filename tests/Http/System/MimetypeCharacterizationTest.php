<?php

declare(strict_types=1);

namespace App\Tests\Http\System;

use App\EventListener\Test\HostMarkerListener;
use App\Tests\Http\Characterization\SnapshotNormalizer;
use App\Tests\Support\HttpTestCase;
use App\Tests\Support\TestSite;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\BrowserKit\Response;

/**
 * Pins the mimetype administration (`admin.php?fct=mimetype`) end to end.
 *
 * The snapshots in __snapshots__/ were recorded from the legacy page
 * (MimetypeLegacyCharacterizationTest, takeover switched off). This class
 * runs the same corpus against the site as configured, where the framework
 * application serves the page (design D11): every response must equal the
 * legacy snapshot, the forms after removing the security token that only the
 * framework page adds.
 *
 * Saves and deletes submit the forms the page itself rendered, with their
 * token when there is one. Their effect on the database is asserted directly.
 * Each case starts from a freshly restored database.
 *
 * Re-record with ICMS_UPDATE_SNAPSHOTS=1 (MimetypeLegacyCharacterizationTest only).
 */
#[Group('spec:system-mimetype-admin')]
class MimetypeCharacterizationTest extends HttpTestCase
{
    use MimetypePage;

    protected const SNAPSHOT_DIR = __DIR__ . '/__snapshots__';

    protected function setUp(): void
    {
        TestSite::restoreSnapshot();
        parent::setUp();
    }

    /**
     * @return array<string, array{string}>
     */
    public static function corpus(): array
    {
        $cases = [
            'list',
            'list-sorted',
            'list-quicksearch',
            'form-create',
            'form-edit',
            'create-post',
            'create-post-redirect-page',
            'update-post',
            'delete-confirmation',
            'delete-confirmed',
            'delete-unknown',
            'denied-anonymous',
            'denied-anonymous-redirect-page',
            'denied-category',
        ];

        return array_combine($cases, array_map(static fn (string $case): array => [$case], $cases));
    }

    /**
     * @scenario List
     * @scenario Snapshot parity
     * @scenario Sorting
     * @scenario Edit form
     * @scenario Create
     * @scenario Confirmed delete
     * @scenario Delete confirmation posted without a query string
     * @scenario Unknown mimetype
     */
    #[DataProvider('corpus')]
    #[Group('spec:system-mimetype-admin')]
    public function testResponseMatchesLegacySnapshot(string $case): void
    {
        [$request, $response, $isForm] = $this->perform($case);

        $actual = (new SnapshotNormalizer(static::$server->baseUri(), stripTokens: $isForm))->transcript($request, $response);
        $this->assertServedAsExpected($case, $response);

        $file = static::SNAPSHOT_DIR . '/mimetype-' . $case . '.txt';
        if (static::recordsSnapshots() && getenv('ICMS_UPDATE_SNAPSHOTS') === '1') {
            @mkdir(static::SNAPSHOT_DIR, 0777, true);
            file_put_contents($file, $actual);
        }

        self::assertFileExists($file, 'No snapshot recorded for ' . $case . '; run MimetypeLegacyCharacterizationTest with ICMS_UPDATE_SNAPSHOTS=1.');
        $expected = str_replace("\r\n", "\n", (string) file_get_contents($file));
        self::assertSame($expected, $actual, "Response for {$case} differs from the legacy snapshot." . self::serverLog());

        $this->assertDatabaseEffects($case);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function adminIntegrationCases(): array
    {
        $cases = ['list', 'create-post', 'create-post-redirect-page', 'denied-anonymous', 'denied-anonymous-redirect-page', 'denied-category'];

        return array_combine($cases, array_map(static fn (string $case): array => [$case], $cases));
    }

    /**
     * The admin theme, the redirects and the denials of the framework page are
     * the legacy ones (a subset of the corpus).
     *
     * @scenario Admin page with a content template
     * @scenario Redirect page mode
     * @scenario Custom redirection mode
     * @scenario Anonymous visitor
     * @scenario Administrator without the category right
     */
    #[DataProvider('adminIntegrationCases')]
    #[Group('spec:admin-page-integration')]
    public function testAdminIntegrationMatchesLegacySnapshot(string $case): void
    {
        $this->testResponseMatchesLegacySnapshot($case);
    }

    /**
     * Whether this class writes the snapshots (only the legacy page may).
     */
    protected static function recordsSnapshots(): bool
    {
        return false;
    }

    /**
     * The takeover answered every request that reached the host. At routing
     * level 2 the web server runs admin.php itself, so legacy answers there.
     */
    protected function assertServedAsExpected(string $case, Response $response): void
    {
        if ($response->getHeader(HostMarkerListener::HEADER) !== null) {
            self::assertSame(self::ROUTE, $response->getHeader(HostMarkerListener::ROUTE_HEADER), 'The takeover did not serve ' . $case . '.');
        }
    }

    /**
     * @return array{string, Response, bool} request line, response, whether the body is a form
     */
    private function perform(string $case): array
    {
        return match ($case) {
            'list' => ['GET ' . self::PAGE . ' (webmaster)', $this->asWebmaster()->request('GET', self::PAGE), false],
            'list-sorted' => [
                'GET ' . self::PAGE . '&mimetype_sortsel=extension&mimetype_ordersel=DESC (webmaster)',
                $this->asWebmaster()->request('GET', self::PAGE . '&mimetype_sortsel=extension&mimetype_ordersel=DESC'),
                false,
            ],
            'list-quicksearch' => [
                'POST ' . self::PAGE . ' quicksearch=Fixture (webmaster)',
                $this->asWebmaster()->request('POST', self::PAGE, ['quicksearch_SystemMimetype' => 'Fixture']),
                false,
            ],
            'form-create' => ['GET ' . self::PAGE . '&op=mod (webmaster)', $this->asWebmaster()->openForm(self::PAGE . '&op=mod')[1], true],
            'form-edit' => [
                'GET ' . self::PAGE . '&op=mod&mimetypeid=' . self::ALPHA . ' (webmaster)',
                $this->asWebmaster()->openForm(self::PAGE . '&op=mod&mimetypeid=' . self::ALPHA)[1],
                true,
            ],
            'create-post' => ['POST op=addmimetype, new (webmaster)', $this->asWebmaster()->createMimetype(), false],
            'create-post-redirect-page' => [
                'POST op=addmimetype, new (webmaster, use_custom_redirection 0)',
                $this->withoutCustomRedirection()->asWebmaster()->createMimetype(),
                false,
            ],
            'update-post' => ['POST op=addmimetype, mimetypeid=' . self::ALPHA . ' (webmaster)', $this->asWebmaster()->updateMimetype(), false],
            'delete-confirmation' => [
                'GET ' . self::PAGE . '&op=del&mimetypeid=' . self::BETA . ' (webmaster)',
                $this->asWebmaster()->openDeleteConfirmation(self::BETA)[1],
                true,
            ],
            'delete-confirmed' => ['POST confirm=1, mimetypeid=' . self::BETA . ' (webmaster)', $this->asWebmaster()->confirmDeletion(self::BETA), false],
            'delete-unknown' => [
                'GET ' . self::PAGE . '&op=del&mimetypeid=9999 (webmaster)',
                $this->asWebmaster()->request('GET', self::PAGE . '&op=del&mimetypeid=9999', [], $this->referer()),
                false,
            ],
            'denied-anonymous' => ['GET ' . self::PAGE . ' (anonymous)', $this->request('GET', self::PAGE), false],
            'denied-anonymous-redirect-page' => [
                'GET ' . self::PAGE . ' (anonymous, use_custom_redirection 0)',
                $this->withoutCustomRedirection()->request('GET', self::PAGE),
                false,
            ],
            'denied-category' => [
                'GET ' . self::PAGE . ' (mediaadmin)',
                $this->loggedInAs(TestSite::MEDIAADMIN_UNAME, TestSite::MEDIAADMIN_PASS)->request('GET', self::PAGE),
                false,
            ],
        };
    }

    private function assertDatabaseEffects(string $case): void
    {
        switch ($case) {
            case 'create-post':
            case 'create-post-redirect-page':
                $row = self::mimetypeRow('icmsd');
                self::assertNotNull($row, 'The new mimetype was not stored.');
                // as legacy stores it: text areas pass the HTML input filter, which marks them
                self::assertSame([
                    'types' => 'application/x-icms-delta' . self::HTML_FILTER_MARKS,
                    'name' => 'ICMS Fixture Delta',
                    'dirname' => 'system',
                ], array_diff_key($row, ['mimetypeid' => 1, 'extension' => 1]));
                self::assertSame([1, 2], self::useExtensionGroups((int) $row['mimetypeid']));
                break;

            case 'update-post':
                $row = self::mimetypeRow('icmsa');
                self::assertNotNull($row);
                self::assertSame('ICMS Fixture Alpha, renamed', $row['name']);
                // a multi-select without selection is not submitted, so legacy keeps the old value
                self::assertSame('system', $row['dirname']);
                self::assertSame([3], self::useExtensionGroups(self::ALPHA));
                break;

            case 'delete-confirmed':
                self::assertNull(self::mimetypeRow('icmsb'));
                self::assertSame([], self::useExtensionGroups(self::BETA));
                break;

            default:
                // nothing changes
                self::assertCount(3, self::fixtureRows());
                self::assertSame([1, 2], self::useExtensionGroups(self::ALPHA));
                self::assertSame([1], self::useExtensionGroups(self::BETA));
        }
    }
}
