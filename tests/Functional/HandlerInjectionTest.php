<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\Support\LegacyWebTestCase;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
final class HandlerInjectionTest extends LegacyWebTestCase
{
    public function testControllerArgumentGetsTheLegacyHandler(): void
    {
        $client = static::createClient();
        $client->request('GET', '/_test/legacy/handler');

        self::assertSame(200, $client->getResponse()->getStatusCode());
        self::assertSame(
            ['class' => \Icms\Data\Page\Handler::class, 'same_as_legacy' => true],
            json_decode((string) $client->getResponse()->getContent(), true),
        );
    }
}
