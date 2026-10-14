<?php

declare(strict_types=1);

namespace App\Security;

use App\Legacy\LegacyRuntime;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Users come from the ImpressCMS session (IcmsSessionAuthenticator). A token
 * Symfony kept in the session is only refreshed while the same user is still
 * logged in to ImpressCMS; otherwise it is dropped.
 *
 * @implements UserProviderInterface<IcmsUser>
 */
final class IcmsUserProvider implements UserProviderInterface
{
    public function __construct(private readonly LegacyRuntime $runtime)
    {
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->runtime->service('user');
        if (!is_object($user) || $user->getVar('uname', 'n') !== $identifier) {
            throw new UserNotFoundException(sprintf('"%s" is not the user logged in to ImpressCMS.', $identifier));
        }

        return IcmsUser::fromLegacy($user);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof IcmsUser) {
            throw new UnsupportedUserException(sprintf('Unsupported user "%s".', $user::class));
        }

        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return $class === IcmsUser::class;
    }
}
