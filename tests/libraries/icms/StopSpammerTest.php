<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

class StopSpammerTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * Test if icms_core_DataFilter is available
     */
    public function testAvailability() {
        $this->assertTrue(class_exists('icms_core_StopSpammer', true), "icms_core_StopSpammer class doesn't exist");        
    }
    
    /**
     * Checks if all required methods are available
     */
    public function testMethodsAvailability() {
        $instance = new \icms_core_StopSpammer();
        foreach ([ 'checkForField', 'badUsername', 'badEmail', 'badIP' ] as $method) {
            $this->assertTrue(method_exists($instance, $method), $method . ' doesn\'t exists');
        }
    }
    
}