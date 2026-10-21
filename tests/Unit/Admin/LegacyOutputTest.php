<?php

declare(strict_types=1);

namespace App\Tests\Unit\Admin;

use App\Admin\LegacyOutput;
use App\LegacyBridge\LegacyResponse;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

/**
 * Output capture for legacy rendering inside the framework. (The CLI has no
 * header list; the Http suite covers headers.)
 */
#[Group('spec:admin-page-integration')]
final class LegacyOutputTest extends TestCase
{
    public function testOutputBecomesTheResponseAndTheBufferLevelIsRestored(): void
    {
        $level = ob_get_level();

        $response = (new LegacyOutput())->capture(static function (): void {
            echo 'header ';
            ob_start();
            echo 'content';
            // a theme that leaves its buffer open
        });

        self::assertInstanceOf(LegacyResponse::class, $response);
        self::assertSame('header content', $response->getContent());
        self::assertSame(200, $response->getStatusCode());
        self::assertSame($level, ob_get_level());
    }

    public function testBuffersAreDiscardedWhenRenderingFails(): void
    {
        $level = ob_get_level();

        try {
            (new LegacyOutput())->capture(static function (): void {
                echo 'partial';
                ob_start();
                throw new \RuntimeException('template failed');
            });
            self::fail('The exception was swallowed.');
        } catch (\RuntimeException $e) {
            self::assertSame('template failed', $e->getMessage());
        }

        self::assertSame($level, ob_get_level());
    }
}
