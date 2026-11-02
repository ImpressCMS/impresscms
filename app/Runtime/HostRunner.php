<?php

declare(strict_types=1);

namespace App\Runtime;

use App\Http\HostKernel;
use App\Legacy\LegacyEventForwarder;
use App\LegacyBridge\LegacyBridge;
use App\LegacyBridge\LegacyExecutionResponse;
use App\LegacyBridge\PreparedLegacyExecution;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Handles a request through the kernel. Laravel responses are sent right
 * away; for a legacy script the runner prepares the execution and leaves the
 * `require` to public/index.php, which runs it at file scope and then calls
 * completeLegacy() or failLegacy(). Both, and the exit() path, dispatch
 * RequestHandled for the real response, send it and terminate the kernel.
 */
final class HostRunner
{
    private HostKernel $kernel;

    private ?Request $request = null;

    public function __construct(private readonly Application $app)
    {
        $this->kernel = $app->make(Kernel::class);
    }

    /**
     * @return PreparedLegacyExecution|null the legacy script to run at file scope
     */
    public function handle(Request $request): ?PreparedLegacyExecution
    {
        $this->request = $request;
        $response = $this->kernel->handle($request);

        if (! $response instanceof LegacyExecutionResponse) {
            $this->send($response);

            return null;
        }

        $this->app->make(LegacyEventForwarder::class)->install();

        return $this->bridge()->prepare($request, $response->resolution, $this->finishLegacy(...));
    }

    public function completeLegacy(PreparedLegacyExecution $execution): void
    {
        $this->finishLegacy($this->bridge()->complete($execution));
    }

    public function failLegacy(PreparedLegacyExecution $execution, Throwable $exception): void
    {
        $this->finishLegacy($this->bridge()->fail($execution, $exception));
    }

    private function finishLegacy(Response $response): void
    {
        $this->kernel->handled($this->request, $response);
        $this->send($response);
    }

    private function send(Response $response): void
    {
        $response->send();

        // @phpstan-ignore argument.type (documented as Illuminate\Http\Response, used with any Symfony response)
        $this->kernel->terminate($this->request, $response);
    }

    private function bridge(): LegacyBridge
    {
        return $this->app->make(LegacyBridge::class);
    }
}
