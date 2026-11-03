<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\LegacyBridge\LegacyExecutionResponse;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Group;

/**
 * Takeovers of legacy URLs are switched off by ICMS_TAKEOVERS_DISABLED, read
 * through config('icms.takeovers.disabled') (design L26), and a route can
 * match a parameter that only the POST body carries (`whereInput()`).
 */
#[Group('spec:request-ownership')]
final class TakeoverSwitchTest extends TestCase
{
    private const URL = '/modules/system/admin.php?fct=users';

    /**
     * @scenario Takeover enabled
     */
    public function testEnabledTakeoverIsServedByLaravel(): void
    {
        $response = $this->get(self::URL);

        self::assertNotInstanceOf(LegacyExecutionResponse::class, $response->baseResponse);
        self::assertSame('host: system users', $response->getContent());
    }

    /**
     * @scenario Takeover disabled
     */
    public function testDisabledTakeoverFallsBackToLegacy(): void
    {
        config(['icms.takeovers.disabled' => ['other.page', 'test.users']]);

        $response = $this->get(self::URL)->baseResponse;

        self::assertInstanceOf(LegacyExecutionResponse::class, $response);
        self::assertSame('/modules/system/admin.php', $response->resolution->scriptName);
    }

    public function testOwnershipCommandNamesTheDisabledTakeover(): void
    {
        config(['icms.takeovers.disabled' => ['test.users']]);

        self::assertSame(0, Artisan::call('icms:routes:ownership', ['url' => self::URL]));
        self::assertStringContainsString('takeover test.users disabled (route test.takeover.system_users)', Artisan::output());
    }

    public function testInputParameterMatchesTheQueryOrElseTheBody(): void
    {
        Route::post('/_test/input.php', static fn (): string => 'host: input')
            ->whereInput(['fct' => 'mimetype']);

        self::assertSame('host: input', $this->post('/_test/input.php', ['fct' => 'mimetype'])->getContent());
        self::assertSame('host: input', $this->post('/_test/input.php?fct=mimetype', ['fct' => 'other'])->getContent());
        self::assertInstanceOf(LegacyExecutionResponse::class, $this->post('/_test/input.php?fct=other', ['fct' => 'mimetype'])->baseResponse);
        self::assertInstanceOf(LegacyExecutionResponse::class, $this->post('/_test/input.php')->baseResponse);
    }

    public function testDisabledKeysAreTrimmed(): void
    {
        $config = require dirname(__DIR__, 2) . '/config/icms.php';

        self::assertIsArray($config['takeovers']['disabled']);
        putenv('ICMS_TAKEOVERS_DISABLED= a , b ,');

        try {
            $reloaded = require dirname(__DIR__, 2) . '/config/icms.php';
        } finally {
            putenv('ICMS_TAKEOVERS_DISABLED');
        }

        self::assertSame(['a', 'b'], $reloaded['takeovers']['disabled']);
    }
}
