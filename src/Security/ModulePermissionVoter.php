<?php

declare(strict_types=1);

namespace App\Security;

use App\Legacy\LegacyRuntime;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * ImpressCMS group permissions for Symfony routes (design D11):
 *
 *     #[IsGranted('icms_perm:module_admin')]            // module-level right of the current module
 *     #[IsGranted('icms_perm:news_submit', subject: 7)] // item right inside the current module
 *
 * Without a subject the right is checked for the current module itself (as
 * module_read/module_admin are, in the system module); with an integer
 * subject for that item of the current module. Anonymous visitors are checked
 * with the anonymous group. Uses icms_member_groupperm, so webmasters always pass.
 *
 * @extends Voter<string, int|null>
 */
final class ModulePermissionVoter extends Voter
{
    public const PREFIX = 'icms_perm:';

    /** the system module, which holds the module-level rights */
    private const SYSTEM_MODULE_ID = 1;

    public function __construct(private readonly LegacyRuntime $runtime)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return str_starts_with($attribute, self::PREFIX) && ($subject === null || is_int($subject));
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $this->runtime->bootTo('module');
        $user = $token->getUser();
        $groups = $user instanceof IcmsUser ? $user->getGroups() : [(int) ICMS_GROUP_ANONYMOUS];

        $module = \icms::$module;
        $moduleId = is_object($module) ? (int) $module->getVar('mid') : self::SYSTEM_MODULE_ID;
        [$itemId, $permissionModule] = $subject === null ? [$moduleId, self::SYSTEM_MODULE_ID] : [$subject, $moduleId];

        $granted = (bool) \icms::handler('icms_member_groupperm')->checkRight(
            substr($attribute, strlen(self::PREFIX)),
            $itemId,
            $groups,
            $permissionModule,
        );
        $vote?->addReason(sprintf('ImpressCMS permission "%s" on item %d of module %d: %s.', $attribute, $itemId, $permissionModule, $granted ? 'granted' : 'denied'));

        return $granted;
    }
}
