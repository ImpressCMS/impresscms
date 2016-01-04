<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class SecurityTest extends \PHPUnit_Framework_TestCase {

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
        $this->assertTrue(method_exists('icms_core_Session', 'service'), 'static method service doesn\'t exists for icms_core_Session');
        $this->assertTrue(\icms_core_Session::service() instanceof \icms_core_Session, 'service method doesn\'t return instanceod icms_core_Session type');
    }

    /**
     * Checks if all required variables are available
     */
    public function testVariables() {
        $instance = \icms_core_Session::service();
        $this->assertInternalType('int', $instance->ipv6securityLevel, 'ipv6securityLevel must be int');
        $this->assertInternalType('int', $instance->securityLevel, 'securityLevel must be int');
        $this->assertInternalType('bool', $instance->enableRegenerateId, 'enableRegenerateId must be bool');
    }

    /**
     * Checks if all required methods are available
     */
    public function testMethodsAvailability() {
        $instance = \icms_core_Session::service();
        foreach ([ 'open', 'close', 'read', 'write', 'destroy', 'gc', 'gc_force', 'icms_sessionRegenerateId', 'update_cookie', 'createFingerprint', 'checkFingerprint', 'sessionOpen', 'removeExpiredCustomSession', 'sessionClose', 'sessionStart' ] as $method) {
            $this->assertTrue(method_exists($instance, $method), $method . ' doesn\'t exists');
        }
    }

}