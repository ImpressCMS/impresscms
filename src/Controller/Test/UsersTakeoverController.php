<?php

declare(strict_types=1);

namespace App\Controller\Test;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Test-only route that claims one variant of a legacy URL: the users page of
 * the system admin (`fct=users`). Other `fct` values stay legacy.
 */
final class UsersTakeoverController
{
    #[Route(
        '/modules/system/admin.php',
        name: 'test_takeover_system_users',
        condition: "request.query.get('fct') == 'users'",
        env: 'test',
    )]
    public function __invoke(): Response
    {
        return new Response('symfony: system users', Response::HTTP_OK, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
