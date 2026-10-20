<?php

declare(strict_types=1);

namespace App\Admin;

use App\Security\IcmsUser;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Access to a function of the system module's administration, with the rules
 * of modules/system/admin.php (design D5, App\Admin\SystemAdminAccess):
 *
 *     #[IsGranted('icms_system_admin:mimetype')]
 *
 * Granted to users who administer the system module and are webmasters or
 * hold `system_admin` for the function's category. Pages that take over a
 * legacy admin function use #[SystemAdminFunction] instead, which also
 * reproduces the legacy denials.
 *
 * @extends Voter<string, mixed>
 */
final class SystemAdminFunctionVoter extends Voter
{
    public const PREFIX = 'icms_system_admin:';

    public function __construct(private readonly SystemAdminAccess $access)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return str_starts_with($attribute, self::PREFIX);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $fct = substr($attribute, strlen(self::PREFIX));
        $user = $token->getUser();
        $groups = $user instanceof IcmsUser ? $user->getGroups() : null;

        $granted = $this->access->isGranted($fct, $groups);
        $vote?->addReason(sprintf('System admin function "%s": %s.', $fct, $granted ? 'granted' : 'denied'));

        return $granted;
    }
}
