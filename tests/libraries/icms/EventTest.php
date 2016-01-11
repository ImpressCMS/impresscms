<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class EventTest extends \PHPUnit_Framework_TestCase {

    /**
     * Test if icms_core_DataFilter is available
     */
    public function testAvailability() {
        $this->assertTrue(class_exists('icms_Event', true), "icms_Event class doesn't exist");
    }

    /**
     * Checks if all required static methods are available
     */
    public function testStaticMethodsAvailability() {
         foreach ([ 'current', 'attach', 'detach', 'trigger' ] as $method) {
             $this->assertTrue(method_exists('icms_Event', $method), $method . ' doesn\'t exists for icms_Event');
         }
    }

    /**
     * Checks if all required variables are available
     */
    public function testVariables() {
        $instance = new \icms_Event('test', 'test', $this);
        $this->assertInternalType('string', $instance->namespace, 'namespace must be string');
        $this->assertInternalType('string', $instance->name, 'name must be string');
        $this->assertInternalType('object', $instance->source, 'source must be object');
        $this->assertInternalType('array', $instance->parameters, 'parameters must be array');
        $this->assertInternalType('bool', $instance->canCancel, 'canCancel must be bool');
        $this->assertInternalType('bool', $instance->canceled, 'canceled must be bool');

    }

}