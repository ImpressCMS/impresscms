<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Legacy\HandlerLocator;
use App\Legacy\LegacyRuntime;
use App\Tests\Support\LegacyWebTestCase;
use Icms\Kernel\Bootstrap;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * ImpressCMS services and handlers in the Symfony container are the
 * instances legacy code uses (design D8).
 */
#[RunTestsInSeparateProcesses]
#[Group('spec:shared-runtime')]
final class ContainerExposureTest extends LegacyWebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
    }

    /**
     * @scenario Same security service
     */
    public function testSecurityServiceIsTheLegacyInstance(): void
    {
        $security = static::getContainer()->get('icms.security');

        self::assertSame(\icms::$security, $security);
        self::assertSame($security, static::getContainer()->get(\Icms\Core\Security::class), 'autowiring alias');
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function services(): array
    {
        return [
            'db' => ['icms.db', 'db'],
            'legacy db' => ['icms.db.legacy', 'xoopsDB'],
            'config' => ['icms.config', 'config'],
            'logger' => ['icms.logger', 'logger'],
            'session' => ['icms.session', 'session'],
            'preload' => ['icms.preload', 'preload'],
        ];
    }

    #[DataProvider('services')]
    public function testCoreServicesAreTheLegacyInstances(string $id, string $property): void
    {
        $service = static::getContainer()->get($id);

        self::assertIsObject($service);
        self::assertSame(\icms::${$property}, $service);
    }

    public function testServicesBootOnlyTheStageTheyNeed(): void
    {
        static::getContainer()->get('icms.config');

        self::assertTrue(Bootstrap::isCompleted('services'));
        self::assertFalse(Bootstrap::isCompleted('module'));

        static::getContainer()->get('icms.module');
        self::assertTrue(Bootstrap::isCompleted('module'));
    }

    /**
     * @scenario Module IPF handler
     */
    public function testIpfHandlerIsTheLegacyInstanceByLegacyAndModernName(): void
    {
        /** @var HandlerLocator $handlers */
        $handlers = static::getContainer()->get('icms.handlers');

        $byLegacyName = $handlers->get('icms_data_page');
        $byModernClass = $handlers->get(\Icms\Data\Page\Handler::class);

        self::assertInstanceOf(\Icms\Ipf\Handler::class, $byLegacyName);
        self::assertSame(\icms::handler('icms_data_page'), $byLegacyName);
        self::assertSame($byLegacyName, $byModernClass);

        $fromContainer = $byLegacyName->create();
        $fromLegacy = \icms::handler('icms_data_page')->create();
        self::assertSame($fromLegacy::class, $fromContainer::class);
        self::assertSame(array_keys($fromLegacy->getVars()), array_keys($fromContainer->getVars()));
    }

    public function testCoreHandlerByLegacyAndModernName(): void
    {
        $handlers = static::getContainer()->get('icms.handlers');

        $byLegacyName = $handlers->get('icms_member');
        $byModernClass = $handlers->get(\Icms\Member\Handler::class);

        self::assertSame(\icms::handler('icms_member'), $byLegacyName);
        self::assertSame($byLegacyName, $byModernClass);
    }

    public function testModuleHandler(): void
    {
        $handlers = static::getContainer()->get('icms.handlers');

        $handler = $handlers->get('module:system/userrank');

        self::assertSame(\icms_getModuleHandler('userrank', 'system'), $handler);
    }

    /**
     * @scenario Unknown handler
     */
    public function testUnknownHandlerIsServiceNotFound(): void
    {
        $handlers = static::getContainer()->get('icms.handlers');

        self::assertFalse($handlers->has('icms_does_not_exist'));
        $this->expectException(ServiceNotFoundException::class);
        $handlers->get('icms_does_not_exist');
    }

    public function testLegacyRuntimeServiceAccessor(): void
    {
        $runtime = static::getContainer()->get(LegacyRuntime::class);

        $security = $runtime->service('security');

        self::assertSame(\icms::$security, $security);
        self::assertNull($runtime->service('user'), 'anonymous: no current user');
    }
}
