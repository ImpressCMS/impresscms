<?php

declare(strict_types=1);

namespace App\Runtime;

use App\Legacy\LegacyEventForwarder;
use App\LegacyBridge\LegacyBridge;
use App\LegacyBridge\LegacyExecutionResponse;
use App\LegacyBridge\PreparedLegacyExecution;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;
use Symfony\Component\Runtime\RunnerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Handles a request through the kernel. Symfony responses are sent right away;
 * for a legacy script the runner prepares the execution and leaves the
 * `require` to host.php, which runs it at file scope and then calls
 * completeLegacy() or failLegacy(). Both paths, and the exit() path, dispatch
 * kernel.response for the real response, send it and terminate the kernel.
 */
final class HostRunner implements RunnerInterface
{
    private ?LegacyExecutionResponse $legacyMarker = null;

    public function __construct(
        private readonly HttpKernelInterface $kernel,
        private readonly Request $request,
        private readonly bool $debug = false,
    ) {
    }

    public function run(): int
    {
        $response = $this->kernel->handle($this->request);

        if ($response instanceof LegacyExecutionResponse) {
            $this->legacyMarker = $response;

            return 0;
        }

        $this->send($response);

        return 0;
    }

    /**
     * The legacy script to run at file scope, prepared, or null for Symfony responses.
     */
    public function pendingLegacyExecution(): ?PreparedLegacyExecution
    {
        if ($this->legacyMarker === null) {
            return null;
        }
        $resolution = $this->legacyMarker->resolution;
        $this->legacyMarker = null;
        $this->container()->get(LegacyEventForwarder::class)->install();

        return $this->bridge()->prepare($this->request, $resolution, $this->finishLegacy(...));
    }

    public function completeLegacy(PreparedLegacyExecution $execution): int
    {
        $this->finishLegacy($this->bridge()->complete($execution));

        return 0;
    }

    public function failLegacy(PreparedLegacyExecution $execution, \Throwable $exception): int
    {
        $this->finishLegacy($this->bridge()->fail($execution, $exception));

        return 0;
    }

    /**
     * kernel.response already ran for the marker (and was stopped there), so it
     * runs here for the legacy response, with the request back on the stack.
     */
    private function finishLegacy(Response $response): void
    {
        $container = $this->container();
        /** @var RequestStack $requestStack */
        $requestStack = $container->get('request_stack');
        /** @var EventDispatcherInterface $dispatcher */
        $dispatcher = $container->get('event_dispatcher');

        $requestStack->push($this->request);
        try {
            $event = new ResponseEvent($this->kernel, $this->request, HttpKernelInterface::MAIN_REQUEST, $response);
            $dispatcher->dispatch($event, KernelEvents::RESPONSE);
            $response = $event->getResponse();
        } finally {
            $requestStack->pop();
        }

        $this->send($response);
    }

    private function send(Response $response): void
    {
        $response->send(false);

        if (\function_exists('fastcgi_finish_request') && !$this->debug) {
            fastcgi_finish_request();
        } elseif (\function_exists('litespeed_finish_request') && !$this->debug) {
            litespeed_finish_request();
        } else {
            Response::closeOutputBuffers(0, true);
            flush();
        }

        if ($this->kernel instanceof TerminableInterface) {
            $this->kernel->terminate($this->request, $response);
        }
    }

    private function bridge(): LegacyBridge
    {
        /** @var LegacyBridge */
        return $this->container()->get(LegacyBridge::class);
    }

    private function container(): \Symfony\Component\DependencyInjection\ContainerInterface
    {
        if (!$this->kernel instanceof KernelInterface) {
            throw new \LogicException('The legacy bridge needs a Symfony kernel.');
        }

        return $this->kernel->getContainer();
    }
}
