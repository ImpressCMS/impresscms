<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

use ImpressCMS\Core\IPF\AbstractDatabaseHandler;
use ImpressCMS\Core\IPF\AbstractDatabaseModel;
use ImpressCMS\Core\Providers\ConfigServiceProvider;
use League\Container\Container;

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
           foreach (['Handler' => AbstractDatabaseHandler::class, 'Object' => AbstractDatabaseModel::class] as $type => $instanecOfType) {
               $class = 'icms_config_' . $name . '_' . $type;
               $this->assertTrue(class_exists($class, true), $class . " class doesn't exist");

               $instance = $this->getMockBuilder($class)
                       ->disableOriginalConstructor()
                       ->getMock();
               $this->assertInternalType('object', $instance, $class. ' is not an object');
               $this->assertInstanceOf($instanecOfType, $instance, $class . ' doesn\'t extend ' . $instanecOfType);
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
		$container = new Container();
		$container->addServiceProvider(ConfigServiceProvider::class);
		$service = $container->get('config');
        $this->assertInstanceOf(\icms_config_Handler::class, $service, 'service method can\'t create good instance');
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
            if (substr($name, 0, 9) !== 'CATEGORY_') {
                continue;
            }
            $cat_constants_count++;
            $this->assertInternalType('int', $value, $name . ' constant value is not of int type');
        }
        $this->assertGreaterThan(1, $cat_constants_count, 'No CATEGORY_* constants found in icms_config_Handler');
    }

}
