<?php

declare(strict_types=1);

namespace App\Controller\Test;

use App\Security\Attribute\RequiresLegacyToken;
use App\Security\IcmsUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Test-only routes for the shared session and identity.
 */
final class IdentityTestController
{
    /**
     * The visitor as Symfony sees it, without access restriction.
     */
    #[Route('/_test/identity', name: 'test_identity', methods: ['GET'], env: 'test')]
    public function identity(#[CurrentUser] ?IcmsUser $user, Request $request): JsonResponse
    {
        $session = $request->getSession();

        return new JsonResponse([
            'authenticated' => $user !== null,
            'id' => $user?->getId(),
            'uname' => $user?->getUname(),
            'groups' => $user?->getGroups() ?? [(int) ICMS_GROUP_ANONYMOUS],
            'legacy_user_id' => is_object(\icms::$user) ? (int) \icms::$user->getVar('uid') : null,
            'legacy_groups' => is_object(\icms::$user) ? array_map('intval', \icms::$user->getGroups()) : null,
            'session_name' => $session->getName(),
            'session_user_id' => $session->get('xoopsUserId'),
        ]);
    }

    #[Route('/_test/members-only', name: 'test_members_only', methods: ['GET'], env: 'test')]
    #[IsGranted('ROLE_USER')]
    public function membersOnly(): Response
    {
        return new Response('members only', Response::HTTP_OK, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    #[Route('/_test/module-admin', name: 'test_module_admin', methods: ['GET'], env: 'test')]
    #[IsGranted('icms_perm:module_admin')]
    public function moduleAdmin(): Response
    {
        return new Response('module admin', Response::HTTP_OK, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    #[Route('/_test/module-read', name: 'test_module_read', methods: ['GET'], env: 'test')]
    #[IsGranted('icms_perm:module_read')]
    public function moduleRead(): Response
    {
        return new Response('module read', Response::HTTP_OK, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    #[Route('/_test/token', name: 'test_token', methods: ['POST'], env: 'test')]
    #[RequiresLegacyToken]
    public function token(): Response
    {
        return new Response('token accepted', Response::HTTP_OK, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
