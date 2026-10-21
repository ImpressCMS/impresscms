<?php

declare(strict_types=1);

namespace App\LegacyBridge;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Maps a request path onto a file under the legacy root.
 *
 * Paths are decoded once, separators are normalised and `..` segments are
 * refused before touching the file system; the resolved real path must stay
 * inside the legacy root (symlinks included) and outside the non-public
 * directories. Every refusal is a 404 that names no file system path.
 */
final class LegacyScriptResolver
{
    private readonly string $legacyRoot;

    /** @var list<string> */
    private readonly array $nonPublicPaths;

    /** @var list<string> */
    private readonly array $scriptFreePaths;

    /**
     * @param list<string> $assetExtensions extensions served as static files
     * @param list<string> $nonPublicPaths  directories (relative to the legacy root, or absolute) never served
     * @param list<string> $scriptFreePaths directories whose PHP files are never executed
     */
    public function __construct(
        string $legacyRoot,
        private readonly string $frontController,
        private readonly array $assetExtensions,
        array $nonPublicPaths = [],
        array $scriptFreePaths = [],
    ) {
        $root = realpath($legacyRoot);
        if ($root === false) {
            throw new \InvalidArgumentException(sprintf('The legacy root "%s" does not exist.', $legacyRoot));
        }
        $this->legacyRoot = self::normalize($root);
        $this->nonPublicPaths = $this->absolutePaths($nonPublicPaths);
        $this->scriptFreePaths = $this->absolutePaths($scriptFreePaths);
    }

    public function legacyRoot(): string
    {
        return $this->legacyRoot;
    }

    public function resolve(Request $request): LegacyResolution
    {
        return $this->resolvePath($request->getPathInfo());
    }

    public function resolvePath(string $pathInfo): LegacyResolution
    {
        $requestPath = '/' . ltrim(str_replace('\\', '/', rawurldecode($pathInfo)), '/');

        if (str_contains($requestPath, "\0") || in_array('..', explode('/', $requestPath), true)) {
            throw new NotFoundHttpException('Unsafe legacy path.');
        }

        $relativePath = trim($requestPath, '/');
        $candidate = $relativePath === '' ? $this->legacyRoot : $this->legacyRoot . '/' . $relativePath;
        // Refused whether or not the file exists, so that nothing falls back to index.php.
        $this->assertPublic($candidate);

        if (is_dir($candidate)) {
            foreach (['index.php', 'index.html'] as $directoryIndex) {
                if (is_file($candidate . '/' . $directoryIndex)) {
                    return $this->build($requestPath, $candidate . '/' . $directoryIndex);
                }
            }
        }

        if (is_file($candidate)) {
            return $this->build($requestPath, $candidate);
        }

        $extension = strtolower(pathinfo($candidate, PATHINFO_EXTENSION));
        if ($extension !== '' && $extension !== 'php' && in_array($extension, $this->assetExtensions, true)) {
            throw new NotFoundHttpException('Legacy asset not found.');
        }

        return $this->build(
            $requestPath,
            $this->legacyRoot . '/' . $this->frontController,
            LegacyResolution::FALLBACK_FRONT_CONTROLLER,
        );
    }

    private function build(string $requestPath, string $candidate, ?string $strategy = null): LegacyResolution
    {
        $realPath = realpath($candidate);
        if ($realPath === false) {
            throw new NotFoundHttpException('Legacy target not found.');
        }
        $realPath = self::normalize($realPath);

        if (!self::isWithin($realPath, $this->legacyRoot)) {
            throw new NotFoundHttpException('Legacy target outside the legacy root.');
        }
        $this->assertPublic($realPath);

        $extension = strtolower(pathinfo($realPath, PATHINFO_EXTENSION));
        if ($extension !== 'php' && !in_array($extension, $this->assetExtensions, true)) {
            // Only allow-listed static files are served (no .inc, .tpl, .sql, ...).
            throw new NotFoundHttpException('Legacy file type is not served.');
        }

        $strategy ??= $extension === 'php' ? LegacyResolution::DIRECT_SCRIPT : LegacyResolution::DIRECT_ASSET;

        return new LegacyResolution(
            strategy: $strategy,
            requestPath: $requestPath,
            resolvedPath: $realPath,
            workingDirectory: dirname($realPath),
            scriptName: '/' . ltrim(substr($realPath, strlen($this->legacyRoot)), '/'),
        );
    }

    private function assertPublic(string $path): void
    {
        foreach ($this->nonPublicPaths as $directory) {
            if (self::isWithin($path, $directory)) {
                throw new NotFoundHttpException('Legacy target in a non-public directory.');
            }
        }
        if (strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'php') {
            foreach ($this->scriptFreePaths as $directory) {
                if (self::isWithin($path, $directory)) {
                    throw new NotFoundHttpException('Legacy script in a directory without scripts.');
                }
            }
        }
    }

    /**
     * @param list<string|null> $paths empty entries (unset environment variables) are ignored
     * @return list<string>
     */
    private function absolutePaths(array $paths): array
    {
        $absolute = [];
        foreach ($paths as $path) {
            $path = str_replace('\\', '/', (string) $path);
            if ($path === '') {
                continue;
            }
            $isAbsolute = str_starts_with($path, '/') || preg_match('#^[A-Za-z]:/#', $path) === 1;
            $full = $isAbsolute ? $path : $this->legacyRoot . '/' . trim($path, '/');
            $absolute[] = self::normalize(realpath($full) ?: $full);
        }

        return $absolute;
    }

    private static function isWithin(string $path, string $directory): bool
    {
        $directory = rtrim($directory, '/');
        if (DIRECTORY_SEPARATOR === '\\') {
            // Windows file systems are case-insensitive.
            [$path, $directory] = [strtolower($path), strtolower($directory)];
        }

        return $path === $directory || str_starts_with($path, $directory . '/');
    }

    private static function normalize(string $path): string
    {
        return rtrim(str_replace('\\', '/', $path), '/');
    }
}
