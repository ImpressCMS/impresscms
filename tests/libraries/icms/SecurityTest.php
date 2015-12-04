<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

class SecurityTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * Test if icms_core_DataFilter is available
     */
    public function testAvailability() {
        $this->assertTrue(class_exists('icms_core_Security', true), "icms_core_Security class doesn't exist");        
    }
    
    /**
     * Tests service method
     */
    public function testService() {
        $this->assertTrue(method_exists('icms_core_Security', 'service'), 'static method service doesn\'t exists for icms_core_Security');
        $this->assertTrue(\icms_core_Security::service() instanceof \icms_core_Security, 'service method doesn\'t return instanceod icms_core_Security type');
    }
    
    /**
     * Checks if all required methods are available
     */
    public function testMethodsAvailability() {
        $instance = \icms_core_Security::service();
        foreach ([ 'check', 'createToken', 'validateToken', 'clearTokens', 'filterToken', 'garbageCollection', 'checkReferer', 'checkSuperglobals', 'checkBadips', 'getTokenHTML', 'setErrors', 'getErrors' ] as $method) {
            $this->assertTrue(method_exists($instance, $method), $method . ' doesn\'t exists');
        }
    }
    
    /**
     * Test how tokens is working
     */
    public function testToken() {
        $instance = \icms_core_Security::service();
        $new_token = $instance->createToken();
        $this->assertInternalType('string', $new_token, 'Generated token is not type of string');
        $this->assertTrue($instance->validateToken($new_token), 'Can\'t validate correct token');
    }
}