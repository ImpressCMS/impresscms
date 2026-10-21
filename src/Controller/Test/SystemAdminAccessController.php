<?php

declare(strict_types=1);

namespace App\Controller\Test;

use App\Admin\SystemAdminFunctionVoter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Test-only: whether the current user may use a system admin function
 * (App\Admin\SystemAdminFunctionVoter).
 */
final class SystemAdminAccessController
{
    #[Route('/_test/system-admin/{fct}', name: 'test_system_admin_access', methods: ['GET'], env: 'test')]
    public function __invoke(string $fct, AuthorizationCheckerInterface $authorization): Response
    {
        $granted = $authorization->isGranted(SystemAdminFunctionVoter::PREFIX . $fct);

        return new Response($granted ? 'granted' : 'denied', Response::HTTP_OK, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
