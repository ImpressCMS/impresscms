<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

class OnlineHandlerTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * Tests availability
     */
    public function testAvailability() {    
        $this->assertTrue(class_exists('icms_core_OnlineHandler', true), "icms_core_OnlineHandler class doesn't exist");
    }    
    
    /**
     * Checks if all required methods are available
     */
    public function testMethodsAvailability() {
         $mock = $this->getMockForAbstractClass('icms_core_OnlineHandler');
         foreach ([ 'write', 'destroy', 'gc', 'getAll', 'getCount' ] as $method) {
             $this->assertTrue(method_exists($mock, $method), $method . ' doesn\'t exists for icms_core_OnlineHandler');
         }
    }
    
}