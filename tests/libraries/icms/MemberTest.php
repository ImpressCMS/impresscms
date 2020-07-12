<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

use ImpressCMS\Core\Models\AbstractDatabaseHandler;
use ImpressCMS\Core\Models\AbstractDatabaseModel;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class MemberTest extends \PHPUnit_Framework_TestCase {

    /**
     * Test if is available
     */
    public function testAvailability() {
        foreach ([
                'icms_member_Handler' => null,
                'icms_member_user_Handler' => AbstractDatabaseHandler::class,
                'icms_member_user_Object' => AbstractDatabaseModel::class,
                'icms_member_rank_Handler' => AbstractDatabaseHandler::class,
                'icms_member_rank_Object' => AbstractDatabaseModel::class,
                'icms_member_groupperm_Handler' => AbstractDatabaseHandler::class,
                'icms_member_groupperm_Object' => AbstractDatabaseModel::class,
                'icms_member_group_Object' => AbstractDatabaseModel::class,
                'icms_member_group_Handler' => AbstractDatabaseHandler::class,
                'icms_member_group_membership_Handler' => AbstractDatabaseHandler::class,
                'icms_member_group_membership_Object' => AbstractDatabaseModel::class
            ] as $class => $must_be_instance_of) {
                $this->assertTrue(class_exists($class, true), $class . " class doesn't exist");
            if ($must_be_instance_of !== null) {
                $instance = $this->getMockBuilder($class)
                    ->disableOriginalConstructor()
                    ->getMock();
                $this->assertInstanceOf($must_be_instance_of, $instance, $class . ' is not instanceof ' . $must_be_instance_of);
            }
        }
    }

    /**
     * Checks if all required methods are available
     */
    public function testMethodsAvailability() {
        foreach ([
            'icms_member_Handler' => [
                'createGroup',
                'createUser',
                'getGroup',
                'getUser',
                'deleteGroup',
                'deleteUser',
                'insertGroup',
                'insertUser',
                'getGroups',
                'getUsers',
                'getGroupList',
                'getUserList',
                'addUserToGroup',
                'removeUsersFromGroup',
                'getUsersByGroup',
                'getGroupsByUser',
                'icms_getLoginFromUserEmail',
                'loginUser',
                'getUserCount',
                'getUserCountByGroup',
                'updateUserByField',
                'updateUsersByField',
                'activateUser',
                'getUsersByGroupLink',
                'getUserCountByGroupLink',
                'getUserBestGroup'
            ],
            'icms_member_user_Object' => [
                'isGuest',
                'getForm',
                'setGroups',
                'sendWelcomeMessage',
                'newUserNotifyAdmin',
                'getGroups',
                'isAdmin',
                'rank',
                'isActive',
                'isOnline',
                'gravatar',
                'uid',
                'login',
                'logout',
                'isSameAsLoggedInUser'
            ],
            'icms_member_user_Handler' => [
                'delete',
                'deleteAll',
                'userCheck',
                'getUnameFromId',
                'getList'
            ],
            'icms_member_rank_Object' => [
                'getVar',
                'getCloneLink',
                'getRankPicture',
                'getRankTitle'
            ],
            'icms_member_groupperm_Handler' => [
                'deleteByGroup',
                'deleteByModule',
                'checkRight',
                'addRight',
                'getItemIds',
                'getGroupIds'
            ],
            'icms_member_group_membership_Handler' => [
                'getGroupsByUser',
                'getUsersByGroup'
            ]
        ] as $class => $methods) {
            $instance = $this->getMockBuilder($class)
                    ->disableOriginalConstructor()
                    ->getMock();
            foreach ($methods as $method) {
                $this->assertTrue(method_exists($instance, $method), $method . ' doesn\'t exists for ' . $class);
            }
        }
    }

    /**
     * Checks if all required static methods are available
     */
    public function testStaticMethodsAvailability() {
        foreach ([
            'icms_member_user_Handler' => [
                'getUserLink',
                'getUnameFromEmail'
            ],
            'icms_member_user_Object' => [
                'getUnameFromId'
            ]
        ] as $class => $methods) {
            foreach ($methods as $method) {
                $this->assertTrue(method_exists($class, $method), $method . ' doesn\'t exists for ' . $class);
            }
        }
    }

}