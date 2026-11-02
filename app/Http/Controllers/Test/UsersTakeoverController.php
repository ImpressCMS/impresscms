<?php

declare(strict_types=1);

namespace App\Http\Controllers\Test;

use Illuminate\Http\Response;

/**
 * Test-only route that claims one variant of a legacy URL: the users page of
 * the system admin (`fct=users`). Other `fct` values stay legacy.
 */
final class UsersTakeoverController
{
    public function __invoke(): Response
    {
        return new Response('host: system users', Response::HTTP_OK, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
