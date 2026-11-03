<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Legacy\Attributes\IcmsModuleHandler;
use App\Legacy\ModuleHandlerLocator;
use App\Tests\Support\LegacyAppTestCase;
use Illuminate\Container\EntryNotFoundException;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Module handlers in the container are the instances icms_getModuleHandler()
 * gives legacy code (design L34).
 */
#[RunTestsInSeparateProcesses]
final class ModuleHandlerLocatorTest extends LegacyAppTestCase
{
    public function testHandlerIsTheLegacyInstance(): void
    {
        $locator = $this->app->make(ModuleHandlerLocator::class);

        $mimetypes = $locator->get('system', 'mimetype');

        self::assertSame(icms_getModuleHandler('mimetype', 'system'), $mimetypes);
        self::assertInstanceOf(\Icms\Ipf\Handler::class, $mimetypes);
        self::assertTrue($locator->has('system', 'mimetype'));
    }

    public function testAttributeInjectsTheLegacyInstance(): void
    {
        $handler = $this->app->call(static fn (#[IcmsModuleHandler('system', 'mimetype')] object $mimetypes): object => $mimetypes);

        self::assertSame(icms_getModuleHandler('mimetype', 'system'), $handler);
    }

    public function testUnknownHandlerIsNotFound(): void
    {
        $locator = $this->app->make(ModuleHandlerLocator::class);

        self::assertFalse($locator->has('system', 'nosuchhandler'));
        self::assertFalse($locator->has('../system', 'mimetype'));

        $this->expectException(EntryNotFoundException::class);
        $locator->get('system', 'nosuchhandler');
    }
}
