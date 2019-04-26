<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

use ImpressCMS\Core\Providers\DatabaseServiceProvider;
use ImpressCMS\Core\Providers\SessionServiceProvider;
use League\Container\Container;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class SessionTest extends \PHPUnit_Framework_TestCase {

    /**
     * Test if icms_core_DataFilter is available
     */
    public function testAvailability() {
        $this->assertTrue(class_exists('icms_core_Session', true), "icms_core_Security class doesn't exist");
    }

    /**
     * Tests service method
     */
    public function testService() {
		$container = new Container();
		$container->addServiceProvider(DatabaseServiceProvider::class);
		$container->addServiceProvider(SessionServiceProvider::class);
		$this->assertTrue($container->get('session') instanceof \icms_core_Session, 'service method doesn\'t return instanceod icms_core_Session type');
    }

    /**
     * Checks if all required variables are available
     */
    public function testVariables() {
		$container = new Container();
		$container->addServiceProvider(DatabaseServiceProvider::class);
		$container->addServiceProvider(SessionServiceProvider::class);
		$instance = $container->get('session');
        $this->assertInternalType('int', $instance->ipv6securityLevel, 'ipv6securityLevel must be int');
        $this->assertInternalType('int', $instance->securityLevel, 'securityLevel must be int');
        $this->assertInternalType('bool', $instance->enableRegenerateId, 'enableRegenerateId must be bool');
    }

    /**
     * Checks if all required methods are available
     */
    public function testMethodsAvailability() {
		$container = new Container();
		$container->addServiceProvider(DatabaseServiceProvider::class);
		$container->addServiceProvider(SessionServiceProvider::class);
		$instance = $container->get('session');
        foreach ([ 'open', 'close', 'read', 'write', 'destroy', 'gc', 'gc_force', 'icms_sessionRegenerateId', 'update_cookie', 'createFingerprint', 'checkFingerprint', 'sessionOpen', 'removeExpiredCustomSession', 'sessionClose', 'sessionStart' ] as $method) {
            $this->assertTrue(method_exists($instance, $method), $method . ' doesn\'t exists');
        }
    }

}
