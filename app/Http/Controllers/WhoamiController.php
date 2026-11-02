<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Auth\IcmsUser;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;

/**
 * Demonstration route for the shared identity: the ImpressCMS user as seen
 * by Laravel. Webmasters only; registered in the `local` and `test`
 * environments.
 */
final class WhoamiController
{
    public function __invoke(#[CurrentUser] IcmsUser $user): JsonResponse
    {
        return new JsonResponse([
            'id' => $user->getId(),
            'uname' => $user->getUname(),
            'name' => $user->getDisplayName(),
            'groups' => $user->getGroups(),
            'roles' => $user->roles(),
        ]);
    }
}
