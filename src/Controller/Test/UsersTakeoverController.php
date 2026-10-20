<?php

declare(strict_types=1);

namespace App\Controller\Test;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Test-only route that claims one variant of a legacy URL: the users page of
 * the system admin (`fct=users`). Other `fct` values stay legacy. Its takeover
 * key is `test.users` (ICMS_TAKEOVERS_DISABLED).
 */
final class UsersTakeoverController
{
    #[Route(
        '/modules/system/admin.php',
        name: 'test_takeover_system_users',
        // bridge tests that need this legacy URL themselves send X-Icms-Test-Takeover: off
        condition: "request.query.get('fct') == 'users' and request.headers.get('X-Icms-Test-Takeover') != 'off' and service('icms_takeovers').enabled('test.users')",
        env: 'test',
    )]
    public function __invoke(): Response
    {
        return new Response('symfony: system users', Response::HTTP_OK, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
