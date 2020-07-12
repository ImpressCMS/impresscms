<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

use ImpressCMS\Core\Models\AbstractDatabaseHandler;
use ImpressCMS\Core\Models\AbstractDatabaseModel;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class ModuleTest extends \PHPUnit_Framework_TestCase {

    /**
     * Test if is available
     */
    public function testAvailability() {
        foreach ([
                'icms_module_Handler' => AbstractDatabaseHandler::class,
                'icms_module_Object' => AbstractDatabaseModel::class
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
            'icms_module_Handler' => [
                'get',
                'getByDirname',
                'delete',
                'install',
                'uninstall',
                'update',
                'activate',
                'deactivate',
                'change',
                'getTemplate'
            ],
            'icms_module_Object' => [
                'launch',
                'registerClassPath',
                'loadInfoAsVar',
                'getInfo',
                'getDBVersion',
                'mainLink',
                'subLink',
                'loadAdminMenu',
                'getAdminMenu',
                'getAdminHeaderMenu',
                'loadInfo',
                'search',
                'displayAdminMenu',
                'setMessage'
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
            'icms_module_Handler' => [
                'getAvailable',
                'getActive',
				'checkModuleAccess'
            ]
        ] as $class => $methods) {
            foreach ($methods as $method) {
                $this->assertTrue(method_exists($class, $method), $method . ' doesn\'t exists for ' . $class);
            }
        }
    }

}