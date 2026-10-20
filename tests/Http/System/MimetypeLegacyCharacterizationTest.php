<?php

declare(strict_types=1);

namespace App\Tests\Http\System;

use App\EventListener\Test\HostMarkerListener;
use App\Tests\Support\ServerProcess;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\BrowserKit\Response;

/**
 * The mimetype corpus with the takeover switched off
 * (ICMS_TAKEOVERS_DISABLED=system.mimetype): the legacy page answers. This
 * records the snapshots, and proves the rollback: switching the takeover off
 * serves the legacy page again, without a cache clear.
 */
#[Group('spec:system-mimetype-admin')]
#[Group('spec:request-ownership')]
final class MimetypeLegacyCharacterizationTest extends MimetypeCharacterizationTest
{
    protected static function createServer(): ServerProcess
    {
        return ServerProcess::forSite(['ICMS_TAKEOVERS_DISABLED' => 'system.mimetype']);
    }

    protected static function recordsSnapshots(): bool
    {
        return !ServerProcess::forSite()->isExternal();
    }

    protected function assertServedAsExpected(string $case, Response $response): void
    {
        self::assertNotSame(self::ROUTE, $response->getHeader(HostMarkerListener::ROUTE_HEADER), 'The disabled takeover still served ' . $case . '.');
    }
}
