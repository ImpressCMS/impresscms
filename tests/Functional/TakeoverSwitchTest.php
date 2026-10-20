<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\LegacyBridge\LegacyExecutionResponse;
use App\Routing\Takeovers;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Takeovers of legacy URLs are switched off by ICMS_TAKEOVERS_DISABLED, read
 * at runtime: the same compiled container and router serve both states.
 */
#[Group('spec:request-ownership')]
final class TakeoverSwitchTest extends WebTestCase
{
    private const URL = '/modules/system/admin.php?fct=users';

    protected function tearDown(): void
    {
        self::setDisabled(null);
        parent::tearDown();
    }

    /**
     * @scenario Takeover enabled
     */
    public function testEnabledTakeoverIsServedBySymfony(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL);

        self::assertNotInstanceOf(LegacyExecutionResponse::class, $client->getResponse());
        self::assertSame('symfony: system users', $client->getResponse()->getContent());
    }

    /**
     * @scenario Takeover disabled
     */
    public function testDisabledTakeoverFallsBackToLegacyWithoutACacheClear(): void
    {
        $client = static::createClient();
        $client->request('GET', self::URL);
        self::assertNotInstanceOf(LegacyExecutionResponse::class, $client->getResponse());

        // the client boots a fresh kernel from the same compiled cache for each request
        self::setDisabled('other.page, test.users');
        $client->request('GET', self::URL);
        $response = $client->getResponse();
        self::assertInstanceOf(LegacyExecutionResponse::class, $response);
        self::assertSame('/modules/system/admin.php', $response->resolution->scriptName);

        self::setDisabled(null);
        $client->request('GET', self::URL);
        self::assertSame('symfony: system users', $client->getResponse()->getContent());
    }

    public function testOwnershipCommandNamesTheDisabledTakeover(): void
    {
        self::setDisabled('test.users');
        $kernel = self::bootKernel();
        $tester = new CommandTester((new Application($kernel))->find('icms:routes:ownership'));
        $tester->execute(['url' => self::URL]);

        $output = $tester->getDisplay();
        self::assertStringContainsString('owner:  legacy', $output);
        self::assertStringContainsString('takeover "test.users" of route test_takeover_system_users is disabled (ICMS_TAKEOVERS_DISABLED)', $output);
    }

    public function testKeysAreReadFromRouteConditions(): void
    {
        self::assertSame(['system.mimetype'], Takeovers::keysInCondition("request.query.get('fct') == 'mimetype' and service('icms_takeovers').enabled('system.mimetype')"));
        self::assertSame([], Takeovers::keysInCondition("request.query.get('fct') == 'users'"));
        self::assertTrue((new Takeovers(' a , b '))->whileAllEnabled(static fn (): bool => true));
        self::assertSame(['a', 'b'], (new Takeovers(' a , b ,'))->disabledKeys());
        self::assertFalse((new Takeovers('a'))->enabled('a'));
        self::assertTrue((new Takeovers(null))->enabled('a'));
    }

    private static function setDisabled(?string $keys): void
    {
        if ($keys === null) {
            unset($_SERVER[Takeovers::ENV], $_ENV[Takeovers::ENV]);
            putenv(Takeovers::ENV);
        } else {
            $_SERVER[Takeovers::ENV] = $_ENV[Takeovers::ENV] = $keys;
            putenv(Takeovers::ENV . '=' . $keys);
        }
    }
}
