<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

use icms_properties_Handler;
use PHPUnit\Framework\TestCase;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class ObjectTest extends TestCase {

    /**
     * Tests availability
     */
    public function testAvailability() {
        $this->assertTrue(class_exists('icms_core_Object', true), "icms_core_Object class doesn't exist");
        $mock = $this->getMockForAbstractClass('icms_core_Object');
        $this->assertInstanceOf(icms_properties_Handler::class, $mock, "icms_core_Object doesn't extended from icms_properties_Handler");
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