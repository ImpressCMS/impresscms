<?php

declare(strict_types=1);

namespace App\Tests\Unit\Security;

use App\Security\IcmsUser;
use PHPUnit\Framework\TestCase;

final class IcmsUserTest extends TestCase
{
    public function testWebmasterRoles(): void
    {
        $user = new IcmsUser(1, 'webmaster', 'Webmaster', [1, 2], 1);

        self::assertTrue($user->isWebmaster());
        self::assertSame(['ROLE_USER', 'ROLE_WEBMASTER', 'ROLE_GROUP_1', 'ROLE_GROUP_2'], $user->getRoles());
        self::assertSame('webmaster', $user->getUserIdentifier());
    }

    public function testMemberRoles(): void
    {
        $user = new IcmsUser(2, 'member', 'member', [2], 1);

        self::assertFalse($user->isWebmaster());
        self::assertSame(['ROLE_USER', 'ROLE_GROUP_2'], $user->getRoles());
    }
}
