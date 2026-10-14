<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Liveness probe. Does not boot ImpressCMS and opens no database connection.
 */
final class HealthController
{
    #[Route('/up', name: 'health', methods: ['GET'])]
    public function __invoke(): Response
    {
        return new Response('ok', Response::HTTP_OK, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
