<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

use PHPUnit\Framework\TestCase;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class PrivMessageTest extends TestCase {

    /**
     * Test if icms_core_DataFilter is available
     */
    public function testAvailability() {
        foreach (['Handler' => 'icms_ipf_Handler', 'Object' => 'icms_ipf_Object'] as $type => $instanecOfType) {
               $class = 'icms_data_privmessage_' . $type;
               $this->assertTrue(class_exists($class, true), $class . " class doesn't exist");

               $instance = $this->getInstanceWithoutConstructor($class);
               $this->assertInternalType('object', $instance, $class. " is not an object");
               $this->assertTrue($instance instanceof $instanecOfType, $class . ' doesn\'t extend ' . $instanecOfType);
        }
    }

    /**
     * Create instance without constructor
     *
     * @param string $class     Class name
     *
     * @return object
     */
    private function getInstanceWithoutConstructor($class) {
        return $this->getMockBuilder($class)
                       ->disableOriginalConstructor()
                       ->getMock();
    }

    /**
     * Checks if all required methods are available
     */
    public function testMethodsAvailability() {
        foreach ([
            'icms_data_privmessage_Handler' => [
                'setRead'
            ],
            'icms_data_privmessage_Object' => [
            ]
        ] as $class => $methods) {
            $instance = $this->getInstanceWithoutConstructor($class);
            foreach ($methods as $method) {
                $this->assertTrue(method_exists($instance, $method), $method . ' doesn\'t exists for ' . $class);
            }
        }
    }

}