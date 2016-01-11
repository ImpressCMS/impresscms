<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class ConfigTest extends \PHPUnit_Framework_TestCase {

    /**
     * Tests availability
     */
    public function testAvailability() {
       foreach (['category', 'Item', 'option'] as $name) {
           foreach (['Handler' => 'icms_ipf_Handler', 'Object' => 'icms_ipf_Object'] as $type => $instanecOfType) {
               $class = 'icms_config_' . $name . '_' . $type;
               $this->assertTrue(class_exists($class, true), $class . " class doesn't exist");

               $instance = $this->getMockBuilder($class)
                       ->disableOriginalConstructor()
                       ->getMock();
               $this->assertInternalType('object', $instance, $class. " is not an object");
               $this->assertTrue($instance instanceof $instanecOfType, $class . ' doesn\'t extend ' . $instanecOfType);
           }
       }

       $this->assertTrue(class_exists('icms_config_Handler', true), "icms_config_Handler class doesn't exists");
       $instance = $this->getMockBuilder('icms_config_Handler')
                       ->disableOriginalConstructor()
                       ->getMock();
       $this->assertInternalType('object', $instance, "icms_config_Handler can't be an instance");
    }

    /**
     * Tests if config category is ok
     */
    public function testConfigHandler() {
        $this->assertTrue(method_exists('icms_config_Handler', 'service'), 'service() method for config handler doesn\'t exist');
        $service = \icms_config_Handler::service();
        $this->assertTrue($service instanceof \icms_config_Handler, 'service method can\'t create good instance');
        foreach ([ 'createConfig', 'getConfig', 'insertConfig', 'deleteConfig', 'getConfigs', 'getConfigCount', 'getConfigsByCat', 'createConfigOption', 'getConfigOption', 'getConfigOptions', 'getConfigOptionsCount', 'getConfigList' ] as $method) {
            $this->assertTrue(method_exists($service, $method), $method . ' doesm\'t exists');
        }
    }

    /**
     * Tests if config category is ok
     */
    public function testConfigCategoryConstants() {
        $class = new \ReflectionClass('icms_config_Handler');
        $cat_constants_count = 0;
        foreach ($class->getConstants() as $name => $value) {
            if (substr($name, 0, 9) != 'CATEGORY_') {
                continue;
            }
            $cat_constants_count++;
            $this->assertInternalType('int', $value, $name . ' constant value is not of int type');
        }
        $this->assertGreaterThan(1, $cat_constants_count, 'No CATEGORY_* constants found in icms_config_Handler');
    }

}
