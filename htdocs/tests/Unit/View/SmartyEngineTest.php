<?php
/**
 * Smoke test for the Composer-installed Smarty engine and the ImpressCMS
 * plugins that live in Icms/View/Smarty/plugins.
 *
 * Renders with the production delimiters and E_ALL, so PHP 8 notices raised
 * by the engine fail the test.
 *
 * @package icms\tests
 */

declare(strict_types=1);

namespace Icms\Tests\Unit\View;

use PHPUnit\Framework\TestCase;
use Smarty;

final class SmartyEngineTest extends TestCase
{
    private string $compileDir;

    private string $templateDir;

    protected function setUp(): void
    {
        $base = sys_get_temp_dir() . '/icms-smarty-' . bin2hex(random_bytes(4));
        $this->compileDir = "$base/compile";
        $this->templateDir = "$base/templates";

        mkdir($this->compileDir, 0777, true);
        mkdir($this->templateDir, 0777, true);
    }

    protected function tearDown(): void
    {
        foreach ([$this->compileDir, $this->templateDir] as $dir) {
            foreach (glob("$dir/*") ?: [] as $file) {
                unlink($file);
            }
            rmdir($dir);
        }
        rmdir(dirname($this->compileDir));
    }

    private function engine(): Smarty
    {
        $smarty = new Smarty();
        $smarty->left_delimiter = '<{';
        $smarty->right_delimiter = '}>';
        $smarty->compile_dir = $this->compileDir;
        $smarty->template_dir = $this->templateDir;
        $smarty->plugins_dir = [
            dirname(__DIR__, 3) . '/libraries/Icms/View/Smarty/plugins',
            SMARTY_DIR . 'plugins',
        ];

        return $smarty;
    }

    private function render(Smarty $smarty, string $name, string $source): string
    {
        file_put_contents("{$this->templateDir}/$name", $source);

        return $smarty->fetch($name);
    }

    public function testSmartyComesFromComposerPackage(): void
    {
        $file = (new \ReflectionClass(Smarty::class))->getFileName();

        $this->assertStringContainsString('smarty', strtolower((string) $file));
        $this->assertStringNotContainsString('htdocs', str_replace('\\', '/', (string) $file));
        $this->assertTrue(is_file(SMARTY_DIR . 'Smarty.class.php'));
    }

    public function testRendersVariablesWithCustomDelimiters(): void
    {
        $smarty = $this->engine();
        $smarty->assign('name', 'ImpressCMS');

        $this->assertSame('Hello ImpressCMS', $this->render($smarty, 'hello.html', 'Hello <{$name}>'));
    }

    public function testIncludeqPassesVariables(): void
    {
        $smarty = $this->engine();
        file_put_contents("{$this->templateDir}/child.html", '[<{$label}>]');

        $out = $this->render($smarty, 'parent.html', '<{includeq file="child.html" label="x"}>');

        $this->assertSame('[x]', $out);
    }

    public function testForeachqIteratesWithNameAndKey(): void
    {
        $smarty = $this->engine();
        $smarty->assign('items', ['a' => 1, 'b' => 2]);

        $out = $this->render(
            $smarty,
            'loop.html',
            '<{foreachq from=$items key=k item=v name=f}><{$k}>=<{$v}>;<{/foreach}>'
        );

        $this->assertSame('a=1;b=2;', $out);
    }

    public function testStockModifierAndSectionStillWork(): void
    {
        $smarty = $this->engine();
        $smarty->assign('rows', [1, 2, 3]);

        $out = $this->render(
            $smarty,
            'stock.html',
            '<{section name=i loop=$rows}><{$rows[i]|escape}><{/section}>'
        );

        $this->assertSame('123', $out);
    }
}
