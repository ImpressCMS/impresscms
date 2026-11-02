<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\Support\LegacyAppTestCase;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
final class HandlerInjectionTest extends LegacyAppTestCase
{
    public function testControllerArgumentGetsTheLegacyHandler(): void
    {
        $response = $this->get('/_test/legacy/handler');

        $response->assertOk();
        self::assertSame(
            ['class' => \Icms\Data\Page\Handler::class, 'same_as_legacy' => true],
            json_decode((string) $response->getContent(), true),
        );
    }
}
