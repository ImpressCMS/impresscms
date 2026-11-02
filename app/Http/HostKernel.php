<?php

declare(strict_types=1);

namespace App\Http;

use App\LegacyBridge\LegacyExecutionResponse;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Foundation\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Laravel's HTTP kernel, except that RequestHandled is not dispatched for the
 * legacy marker: the real response only exists after public/index.php has run
 * the script at file scope, and handled() dispatches it then (design L5).
 *
 * handle() repeats the parent's body, because the event dispatch is not a
 * separate method there.
 */
final class HostKernel extends Kernel
{
    /**
     * @param Request $request
     * @return Response the parent documents Illuminate\Http\Response, but the
     *                  router returns any Symfony response (files, the marker)
     */
    public function handle($request)
    {
        $this->requestStartedAt = Carbon::now();

        try {
            $request->enableHttpMethodParameterOverride();

            $response = $this->sendRequestThroughRouter($request);
        } catch (Throwable $e) {
            $this->reportException($e);

            $response = $this->renderException($request, $e);
        }

        if (! $response instanceof LegacyExecutionResponse) {
            $this->handled($request, $response);
        }

        return $response;
    }

    public function handled(Request $request, Response $response): void
    {
        // @phpstan-ignore argument.type (documented as Illuminate\Http\Response, used with any Symfony response)
        $this->app['events']->dispatch(new RequestHandled($request, $response));
    }
}
