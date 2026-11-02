<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Response;

/**
 * GET /up: the host is alive. Boots no legacy code and opens no connection.
 *
 * Laravel's own health route (`withRouting(health:)`) renders an HTML page;
 * the spec asks for plain text.
 */
final class HealthController
{
    public function __invoke(): Response
    {
        return new Response('ok', Response::HTTP_OK, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
