<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

class AutoloaderTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * Test if icms_core_DataFilter is available
     */
    public function testAvailability() {
        $this->assertTrue(class_exists('icms_Autoloader', true), "icms_Autoloader class doesn't exist");        
    }
    
    /**
     * Checks if all required static methods are available
     */
    public function testStaticMethodsAvailability() {
         $mock = $this->getMockForAbstractClass('icms_Autoloader');
         foreach ([ 'setup', 'split', 'register', 'import', 'autoload', 'classPath', 'registerLegacy' ] as $method) {
             $this->assertTrue(method_exists($mock, $method), $method . ' doesn\'t exists for icms_Autoloader');
         }
    }
    
}