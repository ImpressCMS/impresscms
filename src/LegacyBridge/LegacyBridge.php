<?php

declare(strict_types=1);

namespace App\LegacyBridge;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Serves legacy assets and runs legacy scripts behind the host.
 *
 * A script run is split in three steps around the file-scope `require` in
 * host.php: prepare() hydrates the superglobals and opens an output
 * buffer, the script runs, and complete() or fail() turn the captured output,
 * status code and headers into a Response and restore the host's state. When
 * the script calls exit(), a shutdown function does the same.
 */
final class LegacyBridge
{
    /** Errors that end a script without an exception. */
    private const FATAL_ERRORS = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR];

    /**
     * @param array<string, string> $mimeTypes extension => Content-Type for assets
     */
    public function __construct(
        private readonly LegacyScriptResolver $resolver,
        private readonly LegacyRequestHydrator $hydrator,
        private readonly array $mimeTypes,
        private readonly bool $debug,
        private readonly LoggerInterface $logger,
        private readonly string $frontController = '',
    ) {
    }

    public function resolve(Request $request): LegacyResolution
    {
        return $this->resolver->resolve($request);
    }

    public function createAssetResponse(Request $request, LegacyResolution $resolution): Response
    {
        $response = new LegacyAssetResponse($resolution->resolvedPath, 200, [], false, null, true, true);
        $response->headers->set('Content-Type', $this->mimeType($resolution->resolvedPath));
        $response->preserveHeaders();
        $response->isNotModified($request);

        return $response;
    }

    /**
     * @param \Closure(Response): void $finisher sends the response and terminates the kernel
     */
    public function prepare(Request $request, LegacyResolution $resolution, \Closure $finisher): PreparedLegacyExecution
    {
        if (!headers_sent()) {
            header_remove();
            http_response_code(200);
        }

        $snapshot = $this->hydrator->hydrate($request, $resolution, $this->resolver->legacyRoot());
        $execution = new PreparedLegacyExecution($request, $resolution, $snapshot, ob_get_level(), $finisher);
        $execution->hostExceptionHandler = self::currentExceptionHandler();
        ob_start();

        // Scripts that call exit() or die() never return to host.php.
        register_shutdown_function(function () use ($execution): void {
            if ($execution->completed) {
                return;
            }
            ($execution->finisher)($this->finalize($execution, $this->fatalErrorException()));
        });

        return $execution;
    }

    public function complete(PreparedLegacyExecution $execution): Response
    {
        return $this->finalize($execution);
    }

    /**
     * A throwable escaped the script. Without the bridge PHP would pass it to the
     * exception handler the script installed (ImpressCMS installs one while
     * booting), so that handler renders the response if there is one; otherwise
     * the bridge renders its error page.
     */
    public function fail(PreparedLegacyExecution $execution, \Throwable $exception): Response
    {
        $handler = self::currentExceptionHandler();
        if ($handler !== null && $handler !== $execution->hostExceptionHandler) {
            try {
                $handler(self::withoutHostFrames($exception, $this->frontController));
            } catch (\Throwable $handlerFailure) {
                return $this->finalize($execution, $handlerFailure);
            }

            return $this->finalize($execution);
        }

        return $this->finalize($execution, $exception);
    }

    /**
     * Without the host the script is the outermost file, so its stack traces end
     * there; drop the frame of the host front controller that required it and
     * everything outside it (the host and the web root's index.php preamble).
     */
    private static function withoutHostFrames(\Throwable $exception, string $frontController): \Throwable
    {
        $frontController = realpath($frontController);
        if ($frontController === false) {
            return $exception;
        }

        $property = new \ReflectionProperty($exception instanceof \Exception ? \Exception::class : \Error::class, 'trace');
        $trace = [];
        foreach ((array) $property->getValue($exception) as $frame) {
            if (isset($frame['file']) && realpath($frame['file']) === $frontController) {
                break;
            }
            $trace[] = $frame;
        }
        $property->setValue($exception, $trace);

        return $exception;
    }

    private static function currentExceptionHandler(): ?callable
    {
        $handler = set_exception_handler(null);
        set_exception_handler($handler);

        return $handler;
    }

    private function finalize(PreparedLegacyExecution $execution, ?\Throwable $exception = null): Response
    {
        if ($execution->completed && $execution->response !== null) {
            return $execution->response;
        }

        try {
            $content = $this->drainOutputBuffers($execution->outputBufferLevel);
            $response = $exception === null
                ? $this->buildResponse($execution->resolution, $content)
                : $this->errorResponse($execution, $exception, $content);
        } finally {
            if (!headers_sent()) {
                header_remove();
            }
            $this->hydrator->restore($execution->snapshot);
            $execution->completed = true;
        }

        return $execution->response = $response;
    }

    private function buildResponse(LegacyResolution $resolution, string $content): Response
    {
        $status = http_response_code();
        $response = LegacyResponse::fromLegacyOutput($content, is_int($status) && $status >= 100 ? $status : 200, headers_list());

        if ($this->debug) {
            $response->headers->set('X-Legacy-Bridge-Strategy', $resolution->strategy);
            $response->headers->set('X-Legacy-Bridge-Script', $resolution->scriptName);
        }

        return $response;
    }

    private function errorResponse(PreparedLegacyExecution $execution, \Throwable $exception, string $capturedOutput): Response
    {
        $this->logger->error('Legacy script {script} failed: {message}', [
            'script' => $execution->resolution->scriptName,
            'message' => $exception->getMessage(),
            'exception' => $exception,
            'request_uri' => $execution->request->getRequestUri(),
            'strategy' => $execution->resolution->strategy,
        ]);

        $status = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
        $headers = $exception instanceof HttpExceptionInterface ? $exception->getHeaders() : [];

        if (!$this->debug) {
            return new Response(self::genericErrorPage($status), $status, ['Content-Type' => 'text/html; charset=UTF-8'] + $headers);
        }

        $lines = [
            'Legacy bridge failure',
            '',
            'Request: ' . $execution->request->getMethod() . ' ' . $execution->request->getRequestUri(),
            'Resolution strategy: ' . $execution->resolution->strategy,
            'Legacy script: ' . $execution->resolution->resolvedPath,
            'Exception: ' . $exception::class . ': ' . $exception->getMessage(),
            'Location: ' . $exception->getFile() . ':' . $exception->getLine(),
            '',
            'Stack trace:',
            $exception->getTraceAsString(),
        ];
        if ($capturedOutput !== '') {
            array_push($lines, '', 'Captured output:', rtrim($capturedOutput));
        }

        return new Response(implode("\n", $lines) . "\n", $status, ['Content-Type' => 'text/plain; charset=UTF-8'] + $headers);
    }

    private static function genericErrorPage(int $status): string
    {
        $title = $status . ' ' . (Response::$statusTexts[$status] ?? 'Error');

        return "<!DOCTYPE html>\n<html><head><meta charset=\"UTF-8\"><title>{$title}</title></head>"
            . "<body><h1>{$title}</h1><p>The page could not be displayed. The error has been logged.</p></body></html>\n";
    }

    /**
     * Collect everything the script printed and return to the host's buffer level,
     * reopening buffers a script closed beyond its own.
     */
    private function drainOutputBuffers(int $level): string
    {
        $content = '';
        while (ob_get_level() > $level) {
            $buffer = ob_get_clean();
            if ($buffer === false) {
                break;
            }
            $content = $buffer . $content;
        }
        while (ob_get_level() < $level) {
            ob_start();
        }

        return $content;
    }

    private function mimeType(string $path): string
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (isset($this->mimeTypes[$extension])) {
            return $this->mimeTypes[$extension];
        }

        $type = function_exists('mime_content_type') ? @mime_content_type($path) : false;

        return is_string($type) && $type !== '' ? $type : 'application/octet-stream';
    }

    private function fatalErrorException(): ?\Throwable
    {
        $error = error_get_last();
        if ($error === null || !in_array($error['type'], self::FATAL_ERRORS, true)) {
            return null;
        }

        return new \ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']);
    }
}
