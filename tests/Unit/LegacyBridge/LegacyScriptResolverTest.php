<?php

declare(strict_types=1);

namespace App\Tests\Unit\LegacyBridge;

use App\LegacyBridge\LegacyResolution;
use App\LegacyBridge\LegacyScriptResolver;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class LegacyScriptResolverTest extends TestCase
{
    private string $base;
    private string $root;

    protected function setUp(): void
    {
        $this->base = str_replace('\\', '/', sys_get_temp_dir()) . '/icms-resolver-' . bin2hex(random_bytes(4));
        $this->root = $this->base . '/htdocs';
        $fs = new Filesystem();
        $fs->dumpFile($this->root . '/index.php', '<?php');
        $fs->dumpFile($this->root . '/user.php', '<?php');
        $fs->dumpFile($this->root . '/modules/system/admin.php', '<?php');
        $fs->dumpFile($this->root . '/modules/news/index.php', '<?php');
        $fs->dumpFile($this->root . '/themes/default/style.css', 'body{}');
        $fs->dumpFile($this->root . '/include/settings.inc', 'secret');
        $fs->dumpFile($this->root . '/templates_c/compiled.php', '<?php');
        $fs->dumpFile($this->root . '/cache/data.txt', 'x');
        $fs->dumpFile($this->root . '/uploads/evil.php', '<?php');
        $fs->dumpFile($this->root . '/uploads/picture.png', 'png');
        $fs->dumpFile($this->root . '/trust/secret.php', '<?php');
        $fs->dumpFile($this->base . '/outside/secret.txt', 'outside');
    }

    protected function tearDown(): void
    {
        (new Filesystem())->remove($this->base);
    }

    private function resolver(): LegacyScriptResolver
    {
        return new LegacyScriptResolver(
            $this->root,
            'index.php',
            ['css', 'png', 'txt'],
            ['templates_c', 'cache', $this->root . '/trust', null],
            ['uploads'],
        );
    }

    public function testExistingScript(): void
    {
        $resolution = $this->resolver()->resolvePath('/modules/system/admin.php');

        self::assertSame(LegacyResolution::DIRECT_SCRIPT, $resolution->strategy);
        self::assertSame('/modules/system/admin.php', $resolution->scriptName);
        self::assertSame(realpath($this->root . '/modules/system'), realpath($resolution->workingDirectory));
    }

    public function testDirectoryIndex(): void
    {
        self::assertSame('/modules/news/index.php', $this->resolver()->resolvePath('/modules/news/')->scriptName);
        self::assertSame('/index.php', $this->resolver()->resolvePath('/')->scriptName);
    }

    public function testAsset(): void
    {
        $resolution = $this->resolver()->resolvePath('/themes/default/style.css');

        self::assertTrue($resolution->isDirectAsset());
    }

    public function testUnknownPathFallsBackToTheFrontController(): void
    {
        $resolution = $this->resolver()->resolvePath('/does-not-exist');

        self::assertSame(LegacyResolution::FALLBACK_FRONT_CONTROLLER, $resolution->strategy);
        self::assertSame('/index.php', $resolution->scriptName);
    }

    public function testMissingAssetIsNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->resolver()->resolvePath('/themes/default/missing.css');
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function refusedPaths(): iterable
    {
        yield 'dot segments' => ['/../outside/secret.txt'];
        yield 'percent-encoded dot segments' => ['/%2e%2e/outside/secret.txt'];
        yield 'mixed-case percent-encoding' => ['/%2E%2e/%2e%2E/outside/secret.txt'];
        yield 'encoded slash' => ['/..%2foutside%2fsecret.txt'];
        yield 'backslash' => ['/..\\outside\\secret.txt'];
        yield 'encoded backslash' => ['/%2e%2e%5coutside%5csecret.txt'];
        yield 'traversal inside a path' => ['/modules/../../outside/secret.txt'];
        yield 'NUL byte' => ['/user.php%00.css'];
        yield 'compiled template' => ['/templates_c/compiled.php'];
        yield 'missing compiled template' => ['/templates_c/missing.php'];
        yield 'cache file' => ['/cache/data.txt'];
        yield 'cache directory' => ['/cache/'];
        yield 'uploaded script' => ['/uploads/evil.php'];
        yield 'missing uploaded script' => ['/uploads/missing.php'];
        yield 'trust path' => ['/trust/secret.php'];
        yield 'file type that is not served' => ['/include/settings.inc'];
    }

    #[DataProvider('refusedPaths')]
    public function testRefusedPathIsNotFound(string $path): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->resolver()->resolvePath($path);
    }

    public function testUploadedNonScriptFilesAreServed(): void
    {
        self::assertTrue($this->resolver()->resolvePath('/uploads/picture.png')->isDirectAsset());
    }

    public function testDoubleEncodingIsDecodedOnce(): void
    {
        // %252e decodes once, to a directory literally named "%2e", which does not exist
        $resolution = $this->resolver()->resolvePath('/%252e%252e/user.php');

        self::assertSame(LegacyResolution::FALLBACK_FRONT_CONTROLLER, $resolution->strategy);
    }

    public function testSymlinkOutOfTheLegacyRootIsRefused(): void
    {
        $link = $this->root . '/escape';
        if (!@symlink($this->base . '/outside', $link)) {
            self::markTestSkipped('Creating symbolic links is not permitted here.');
        }

        $this->expectException(NotFoundHttpException::class);
        $this->resolver()->resolvePath('/escape/secret.txt');
    }

    public function testErrorsNameNoFileSystemPath(): void
    {
        try {
            $this->resolver()->resolvePath('/../outside/secret.txt');
            self::fail('Expected a 404.');
        } catch (NotFoundHttpException $e) {
            self::assertStringNotContainsString($this->base, $e->getMessage());
            self::assertSame(404, $e->getStatusCode());
        }
    }
}
