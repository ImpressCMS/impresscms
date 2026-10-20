<?php

declare(strict_types=1);

namespace App\Tests\Http\System;

use App\Tests\Support\TestSite;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Requests and database checks for the mimetype administration, for Http
 * tests (App\Tests\Support\HttpTestCase).
 */
trait MimetypePage
{
    protected const PAGE = '/modules/system/admin.php?fct=mimetype';

    /** The route of the takeover (App\Controller\System\MimetypeController). */
    protected const ROUTE = 'system_mimetype';

    /** Appended by the legacy HTML input filter to every stored text area. */
    protected const HTML_FILTER_MARKS = '<!-- filtered with htmlpurifier --><!-- input filtered -->';

    /** Fixture mimetype ids, in the order of TestSite::MIMETYPES. */
    protected const ALPHA = 124;
    protected const BETA = 125;

    // -- Requests --------------------------------------------------------------

    protected function asWebmaster(): static
    {
        return $this->loggedInAs(TestSite::WEBMASTER_UNAME, TestSite::WEBMASTER_PASS);
    }

    protected function loggedInAs(string $uname, string $pass): static
    {
        $this->loginAs($uname, $pass);

        return $this;
    }

    protected function withoutCustomRedirection(): static
    {
        TestSite::setConfig('use_custom_redirection', '0');

        return $this;
    }

    /**
     * Open a page with a form, coming from the list (the page before the form).
     *
     * @return array{Crawler, Response}
     */
    protected function openForm(string $path): array
    {
        $crawler = $this->browser->request('GET', static::url($path), [], [], $this->referer());

        return [$crawler, $this->browser->getInternalResponse()];
    }

    /**
     * @return array{Crawler, Response}
     */
    protected function openDeleteConfirmation(int $id): array
    {
        return $this->openForm(self::PAGE . '&op=del&mimetypeid=' . $id);
    }

    protected function createMimetype(): Response
    {
        return $this->submitMimetypeForm(self::PAGE . '&op=mod', [
            'extension' => 'icmsd',
            'types' => 'application/x-icms-delta',
            'name' => 'ICMS Fixture Delta',
            'dirname' => ['system'],
            'use_extension' => ['1', '2'],
        ]);
    }

    protected function updateMimetype(): Response
    {
        return $this->submitMimetypeForm(self::PAGE . '&op=mod&mimetypeid=' . self::ALPHA, [
            'name' => 'ICMS Fixture Alpha, renamed',
            'dirname' => [],
            'use_extension' => ['3'],
        ]);
    }

    /**
     * Submit the create/edit form as a browser would, with $changes applied.
     *
     * @param array<string, string|list<string>> $changes
     */
    protected function submitMimetypeForm(string $formPath, array $changes): Response
    {
        [$crawler, $response] = $this->openForm($formPath);
        self::assertSame(200, $response->getStatusCode(), self::serverLog());

        $form = $crawler->filterXPath('//form[@id="addmimetype"]')->form();
        $values = array_replace($form->getPhpValues(), $changes);
        // an empty multi-select is not submitted at all
        $values = array_filter($values, static fn (mixed $value): bool => $value !== []);

        $this->browser->request('POST', $form->getUri(), $values, [], $this->referer($form->getUri()));

        return $this->browser->getInternalResponse();
    }

    protected function confirmDeletion(int $id): Response
    {
        [$crawler, $response] = $this->openDeleteConfirmation($id);
        self::assertSame(200, $response->getStatusCode(), self::serverLog());

        $form = $crawler->filterXPath('//div[@class="confirmMsg"]//form')->form();
        $this->browser->request('POST', $form->getUri(), $form->getPhpValues(), [], $this->referer());

        return $this->browser->getInternalResponse();
    }

    /**
     * @return array<string, string>
     */
    protected function referer(?string $url = null): array
    {
        return ['HTTP_REFERER' => $url ?? static::url(self::PAGE)];
    }

    // -- Database --------------------------------------------------------------

    /**
     * @return array<string, string>|null
     */
    protected static function mimetypeRow(string $extension): ?array
    {
        $stmt = TestSite::connection()->prepare('SELECT * FROM ' . TestSite::table('system_mimetype') . ' WHERE extension = ?');
        $stmt->execute([$extension]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row === false ? null : array_map('strval', $row);
    }

    /**
     * @return list<array<string, mixed>>
     */
    protected static function fixtureRows(): array
    {
        return TestSite::connection()
            ->query("SELECT * FROM " . TestSite::table('system_mimetype') . " WHERE extension LIKE 'icms%'")
            ->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return list<int>
     */
    protected static function useExtensionGroups(int $mimetypeId): array
    {
        $stmt = TestSite::connection()->prepare(
            'SELECT gperm_groupid FROM ' . TestSite::table('group_permission')
            . " WHERE gperm_name = 'use_extension' AND gperm_modid = 1 AND gperm_itemid = ? ORDER BY gperm_groupid",
        );
        $stmt->execute([$mimetypeId]);

        return array_map('intval', $stmt->fetchAll(\PDO::FETCH_COLUMN));
    }
}
