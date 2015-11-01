<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

class ObjectTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * Tests availability
     */
    public function testAvailability() {    
        $this->assertTrue(class_exists('icms_core_Object', true), "icms_core_Object class doesn't exist");
        $mock = $this->getMockForAbstractClass('icms_core_Object');
        $this->assertTrue($mock instanceof \icms_properties_Handler, "icms_core_Object doesn't extended from icms_properties_Handler");
    }    
    
    /**
     * Checks if all required methods are available
     */
    public function testMethodsAvailability() {
         $mock = $this->getMockForAbstractClass('icms_core_Object');
         foreach ([ 'setNew', 'unsetNew', 'isNew', 'initVar', 'setFormVars', 'registerFilter', '__clone', 'setErrors', 'getErrors', 'getHtmlErrors', 'hasError' ] as $method) {
             $this->assertTrue(method_exists($mock, $method), $method . ' doesn\'t exists for icms_core_Object');
         }
    }
    
}