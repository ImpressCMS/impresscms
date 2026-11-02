<?php

declare(strict_types=1);

namespace App\Http\Controllers\Test;

use App\Auth\IcmsUser;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Test-only routes for the shared session and identity.
 */
final class IdentityTestController
{
    /**
     * The visitor as Laravel sees it, without access restriction.
     */
    public function identity(#[CurrentUser] ?IcmsUser $user, Request $request): JsonResponse
    {
        $session = $request->session();

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

    public function membersOnly(): Response
    {
        return self::text('members only');
    }

    public function moduleAdmin(): Response
    {
        return self::text('module admin');
    }

    public function moduleRead(): Response
    {
        return self::text('module read');
    }

    public function token(): Response
    {
        return self::text('token accepted');
    }

    private static function text(string $body): Response
    {
        return new Response($body, Response::HTTP_OK, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
