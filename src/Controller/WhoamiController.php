<?php

declare(strict_types=1);

namespace App\Controller;

use App\Security\IcmsUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Demonstration route for the shared identity: the ImpressCMS user as seen by
 * Symfony. Webmasters only; registered in the dev and test environments.
 */
final class WhoamiController
{
    #[Route('/whoami', name: 'whoami', methods: ['GET'], env: ['dev', 'test'])]
    #[IsGranted('ROLE_WEBMASTER')]
    public function __invoke(#[CurrentUser] IcmsUser $user): JsonResponse
    {
        return new JsonResponse([
            'id' => $user->getId(),
            'uname' => $user->getUname(),
            'name' => $user->getDisplayName(),
            'groups' => $user->getGroups(),
            'roles' => $user->getRoles(),
        ]);
    }
}
