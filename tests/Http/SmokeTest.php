<?php

declare(strict_types=1);

namespace App\Tests\Http;

use App\Tests\Support\HttpTestCase;
use App\Tests\Support\TestSite;

final class SmokeTest extends HttpTestCase
{
    public function testHomePageRenders(): void
    {
        $response = $this->request('GET', '/');

        self::assertSame(200, $response->getStatusCode(), self::serverLog());
        self::assertStringContainsString(TestSite::SITE_NAME, $response->getContent());
    }

    public function testWebmasterCanOpenTheControlPanel(): void
    {
        $this->loginAsWebmaster();
        $response = $this->request('GET', '/admin.php');

        self::assertSame(200, $response->getStatusCode(), self::serverLog());
        self::assertStringContainsString('Administration of ' . TestSite::SITE_NAME, $response->getContent());
    }
}
