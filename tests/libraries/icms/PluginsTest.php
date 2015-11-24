<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

class PluginsTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * Test if is available
     */
    public function testAvailability() {
        foreach ([
            'icms_plugins_EditorHandler',
            'icms_plugins_Handler',
            'icms_plugins_Object'
        ] as $class) {
            $this->assertTrue(class_exists($class, true), $class . ' does\'t exist');
        }                
    }
    
    /**
     * Gets instance of class from classname
     * 
     * @param string $class     ClassName
     * 
     * @return object
     */
    private function getClassInstance($class) {
        $instance = $this->getMockBuilder($class)
                    ->disableOriginalConstructor()
                    ->getMock();
        return $instance;
    }

    /**
     * Test methods availability
     */
    public function testMethodsAvailability() {
        foreach ([
            'icms_plugins_Object' => [
                'getItemInfo',
                'getItemList',
                'getItem',
                'getItemIdForItem'
            ],
            'icms_plugins_Handler' => [
                'getPlugin',
                'getPluginsArray'
            ],
            'icms_plugins_EditorHandler' => [
                'get',
                'getList',
                'render',
                'setConfig',
                '_loadEditor'
            ]
        ] as $class => $methods) {
            foreach ($methods as $method) {
                $this->assertTrue(method_exists($class, $method), 'Static method ' . $method . ' doesn\'t exists for class ' . $class);
            }
        }
    }
    
    /**
     * Test static method availability
     */
    public function testStaticMethodsAvailability() {
        $this->assertTrue(method_exists('icms_plugins_EditorHandler', 'getListByType'), 'Method getListByType doesn\'t exists for class icms_plugins_EditorHandler');
    }    
    
    /**
     * Tests variables availability and types
     */
    public function testVariables() {
        foreach ([
            'icms_plugins_EditorHandler' => [
                'nohtml' => 'bool',
                'allowed_editors' => 'array'
            ],
            'icms_plugins_Handler' => [
                'pluginPatterns' => 'bool'
            ],
            'icms_plugins_Object' => [
                '_infoArray' => 'array'
            ]
        ] as $class => $variables) {
            $instance = $this->getClassInstance($class);
            foreach ($variables as $variable => $type) {
                $this->assertInternalType($type, $instance->$variable, '$' . $variable . ' is not of type ' . $type . ' in instance of ' . $class);
            }
        }
    }    
    
}