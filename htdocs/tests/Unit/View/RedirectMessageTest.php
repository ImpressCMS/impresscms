<?php
/**
 * Verifies the parts of Icms\View\RedirectMessage that do not need a booted CMS: the plain text
 * fallback and the generated inline script. prepare() and addToTheme() depend on the HTML filter
 * configuration and the theme and are covered by testing in the admin.
 *
 * @package icms\tests
 */

declare(strict_types=1);

namespace Icms\Tests\Unit\View;

use Icms\View\RedirectMessage;
use PHPUnit\Framework\TestCase;

final class RedirectMessageTest extends TestCase
{
    public function testPlainTextKeepsLineBreaksOfHeadingsAndBreaks(): void
    {
        self::assertSame(
            "Mislukt\nFout\nIets",
            RedirectMessage::toPlainText('Mislukt<h4>Fout</h4>Iets<br />')
        );
    }

    public function testPlainTextDropsScriptAndStyleContent(): void
    {
        self::assertSame(
            'abc',
            RedirectMessage::toPlainText('a<script>alert(1)</script>b<style>x { color: red; }</style>c')
        );
    }

    public function testPlainTextRemovesOtherTagsAndAttributes(): void
    {
        self::assertSame(
            'vet link',
            RedirectMessage::toPlainText('<b>vet</b> <a href="javascript:x" onclick="y">link</a>')
        );
    }

    public function testPlainTextOfEmptyMessageIsEmpty(): void
    {
        self::assertSame('', RedirectMessage::toPlainText(''));
    }

    public function testScriptCannotBeClosedOrBrokenOutOfByTheMessage(): void
    {
        $hostile = '"</script><script>alert(1)</script>\'&amp; <b>x</b>';
        $script = RedirectMessage::buildScript($hostile, ['type' => 'info', 'html' => true], ['close' => 'Sluit "venster"']);

        self::assertStringNotContainsString('</script', strtolower($script));
        self::assertStringNotContainsString('<', $script);
        self::assertStringNotContainsString("'", $script);
    }

    public function testMessageIsPassedToTheScriptUnchanged(): void
    {
        $message = "Opgeslagen <b>\"vet\"</b> & 'klaar'\nTweede regel";
        $script = RedirectMessage::buildScript($message, ['type' => 'info', 'html' => false], ['close' => 'Close']);

        // the first argument of icms.notify() is a JSON string whose quotes are escaped as "
        self::assertSame(1, preg_match('/icms\.notify\((".*?")/s', $script, $matches));
        self::assertSame($message, json_decode($matches[1]));
    }

    public function testScriptShowsTheMessageWhenThePageIsLoaded(): void
    {
        $script = RedirectMessage::buildScript('x', ['type' => 'error', 'html' => true], ['close' => 'Close']);

        self::assertStringStartsWith('document.addEventListener("DOMContentLoaded"', $script);
        self::assertStringContainsString('icms.notify.labels = ', $script);
        self::assertStringContainsString('error', $script);
    }
}
