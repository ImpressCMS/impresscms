<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

use ImpressCMS\Core\Models\AbstractExtendedHandler;
use ImpressCMS\Core\Models\AbstractExtendedModel;
use PHPUnit\Framework\TestCase;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class PageTest extends TestCase {

    /**
     * Test if icms_core_DataFilter is available
     */
    public function testAvailability() {
        foreach (['Handler' => AbstractExtendedHandler::class, 'Object' => AbstractExtendedModel::class] as $type => $instanecOfType) {
               $class = 'icms_data_page_' . $type;
               $this->assertTrue(class_exists($class, true), $class . " class doesn't exist");

               $instance = $this->getInstanceWithoutConstructor($class);
               $this->assertIsObject( $instance, $class. ' is not an object');
               $this->assertInstanceOf($instanecOfType, $instance, $class . ' doesn\'t extend ' . $instanecOfType);
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
            'icms_data_page_Object' => [
                'getURL'
            ],
            'icms_data_page_Handler' => [
                'getList',
                'getPageSelOptions'
            ]
        ] as $class => $methods) {
            $instance = $this->getInstanceWithoutConstructor($class);
            foreach ($methods as $method) {
                $this->assertTrue(method_exists($instance, $method), $method . ' doesn\'t exists for ' . $class);
            }
        }
    }

}