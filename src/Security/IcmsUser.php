<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * The ImpressCMS user (icms::$user) as the Symfony user (design D11).
 *
 * Roles: ROLE_USER for every logged-in user, ROLE_WEBMASTER for members of
 * the webmasters group (ICMS_GROUP_ADMIN) and ROLE_GROUP_<id> per group.
 */
final class IcmsUser implements UserInterface
{
    /**
     * @param list<int> $groups
     */
    public function __construct(
        private readonly int $id,
        private readonly string $uname,
        private readonly string $displayName,
        private readonly array $groups,
        private readonly int $webmasterGroup,
    ) {
    }

    /**
     * @param object $user an Icms\Member\User\Entity (icms::$user)
     */
    public static function fromLegacy(object $user): self
    {
        $name = trim((string) $user->getVar('name', 'n'));
        $uname = (string) $user->getVar('uname', 'n');

        return new self(
            (int) $user->getVar('uid'),
            $uname,
            $name !== '' ? $name : $uname,
            array_values(array_map('intval', (array) $user->getGroups())),
            (int) ICMS_GROUP_ADMIN,
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUname(): string
    {
        return $this->uname;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    /**
     * @return list<int>
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    public function isWebmaster(): bool
    {
        return in_array($this->webmasterGroup, $this->groups, true);
    }

    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];
        if ($this->isWebmaster()) {
            $roles[] = 'ROLE_WEBMASTER';
        }
        foreach ($this->groups as $group) {
            $roles[] = 'ROLE_GROUP_' . $group;
        }

        return $roles;
    }

    public function getUserIdentifier(): string
    {
        return $this->uname;
    }

    public function eraseCredentials(): void
    {
    }
}
