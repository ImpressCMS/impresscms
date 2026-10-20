<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Legacy\ModuleHandlerLocator;
use App\Tests\Support\LegacyWebTestCase;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Module handlers in the container are the instances icms_getModuleHandler()
 * gives legacy code (design D10).
 */
#[RunTestsInSeparateProcesses]
final class ModuleHandlerLocatorTest extends LegacyWebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
    }

    public function testHandlerIsTheLegacyInstance(): void
    {
        $locator = static::getContainer()->get('icms.module_handlers');
        self::assertInstanceOf(ModuleHandlerLocator::class, $locator);

        $mimetypes = $locator->get('system', 'mimetype');

        self::assertSame(icms_getModuleHandler('mimetype', 'system'), $mimetypes);
        self::assertInstanceOf(\Icms\Ipf\Handler::class, $mimetypes);
        self::assertTrue($locator->has('system', 'mimetype'));
    }

    public function testUnknownHandlerIsNotFound(): void
    {
        $locator = static::getContainer()->get('icms.module_handlers');

        self::assertFalse($locator->has('system', 'nosuchhandler'));
        self::assertFalse($locator->has('../system', 'mimetype'));

        $this->expectException(ServiceNotFoundException::class);
        $locator->get('system', 'nosuchhandler');
    }
}
