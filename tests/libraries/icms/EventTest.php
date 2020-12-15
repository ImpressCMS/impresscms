<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

use ImpressCMS\Core\Event;
use PHPUnit\Framework\TestCase;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class EventTest extends TestCase {

    /**
     * Test if icms_core_DataFilter is available
     */
    public function testAvailability() {
        $this->assertTrue(class_exists('\\ImpressCMS\\Core\\Event', true), "Event class doesn't exist");
    }

    /**
     * Checks if all required static methods are available
     */
    public function testStaticMethodsAvailability() {
         foreach ([ 'current', 'attach', 'detach', 'trigger' ] as $method) {
             $this->assertTrue(method_exists('\\ImpressCMS\\Core\\Event', $method), $method . ' doesn\'t exists for Event');
         }
    }

    /**
     * Checks if all required variables are available
     */
    public function testVariables() {
        $instance = new Event('test', 'test', $this);
        $this->assertIsString( $instance->namespace, 'namespace must be string');
        $this->assertIsString( $instance->name, 'name must be string');
        $this->assertIsObject( $instance->source, 'source must be object');
        $this->assertIsArray( $instance->parameters, 'parameters must be array');
        $this->assertIsBool( $instance->canCancel, 'canCancel must be bool');
        $this->assertIsBool( $instance->canceled, 'canceled must be bool');

    }

}