<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

class HandlerTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * Tests availability
     */
    public function testAvailability() {    
        $this->assertTrue(class_exists('icms_core_ObjectHandler', true), "icms_core_Object class doesn't exist");
    }    
    
    /**
     * Checks if all required variables are available
     */
    public function testVariablesAvailability() {
         $mock = $this->getMockForAbstractClass('icms_core_ObjectHandler');
         foreach ([ 'db' ] as $variable) {
             $this->assertTrue(property_exists($mock, $variable), '$' . $variable . ' doesn\'t exists for icms_core_ObjectHandler');
         }
    }    
    
    /**
     * Checks if all required methods are available
     */
    public function testMethodsAvailability() {
         $mock = $this->getMockForAbstractClass('icms_core_ObjectHandler');
         foreach ([ 'create', 'get', 'insert', 'delete' ] as $method) {
             $this->assertTrue(method_exists($mock, $method), $method . ' doesn\'t exists for icms_core_ObjectHandler');
         }
    }
    
}