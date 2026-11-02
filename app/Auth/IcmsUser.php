<?php

declare(strict_types=1);

namespace App\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use LogicException;

/**
 * The ImpressCMS user (icms::$user) as the Laravel user (design L11).
 *
 * Login, logout and autologin stay in ImpressCMS, so there is no password or
 * remember token here. roles() names the same roles as the Symfony variant:
 * ROLE_USER for every logged-in user, ROLE_WEBMASTER for members of the
 * webmasters group (ICMS_GROUP_ADMIN) and ROLE_GROUP_<id> per group.
 */
final class IcmsUser implements Authenticatable
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

    public function inGroup(int $group): bool
    {
        return in_array($group, $this->groups, true);
    }

    /**
     * @return list<string>
     */
    public function roles(): array
    {
        $roles = ['ROLE_USER'];
        if ($this->isWebmaster()) {
            $roles[] = 'ROLE_WEBMASTER';
        }

        foreach ($this->groups as $group) {
            $roles[] = "ROLE_GROUP_{$group}";
        }

        return $roles;
    }

    public function getAuthIdentifierName(): string
    {
        return 'uid';
    }

    public function getAuthIdentifier(): int
    {
        return $this->id;
    }

    public function getAuthPasswordName(): string
    {
        return 'pass';
    }

    public function getAuthPassword(): string
    {
        throw new LogicException('ImpressCMS checks passwords itself.');
    }

    public function getRememberToken(): ?string
    {
        return null;
    }

    /**
     * @param string $value
     */
    public function setRememberToken($value): void
    {
        // autologin is ImpressCMS'
    }

    public function getRememberTokenName(): string
    {
        return '';
    }
}
