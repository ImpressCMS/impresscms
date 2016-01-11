<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class DebugTest extends \PHPUnit_Framework_TestCase {

    /**
     * Test if icms_core_DataFilter is available
     */
    public function testAvailability() {
        $this->assertTrue(class_exists('icms_core_Debug', true), "icms_core_Debug class doesn't exist");
    }

    /**
     * Checks if all required static methdos are available
     */
    public function testMethodsAvailability() {
         foreach ([ 'message', 'vardump', 'setDeprecated' ] as $method) {
             $this->assertTrue(method_exists('icms_core_Debug', $method), $method . ' doesm\'t exists');
         }
    }

}