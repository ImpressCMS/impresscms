<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth;

use App\Auth\IcmsUser;
use PHPUnit\Framework\TestCase;

final class IcmsUserTest extends TestCase
{
    public function testWebmasterRoles(): void
    {
        $user = new IcmsUser(1, 'webmaster', 'Webmaster', [1, 2], 1);

        self::assertTrue($user->isWebmaster());
        self::assertSame(['ROLE_USER', 'ROLE_WEBMASTER', 'ROLE_GROUP_1', 'ROLE_GROUP_2'], $user->roles());
        self::assertSame(1, $user->getAuthIdentifier());
    }

    public function testMemberRoles(): void
    {
        $user = new IcmsUser(2, 'member', 'member', [2], 1);

        self::assertFalse($user->isWebmaster());
        self::assertTrue($user->inGroup(2));
        self::assertSame(['ROLE_USER', 'ROLE_GROUP_2'], $user->roles());
    }

    public function testNoPasswordOrRememberToken(): void
    {
        $user = new IcmsUser(2, 'member', 'member', [2], 1);

        self::assertNull($user->getRememberToken());
        $this->expectException(\LogicException::class);
        $user->getAuthPassword();
    }
}
