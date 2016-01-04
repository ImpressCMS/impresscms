<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class CommentTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * Test if icms_core_DataFilter is available
     */
    public function testAvailability() {
        foreach (['Handler' => 'icms_ipf_Handler', 'Object' => 'icms_ipf_Object'] as $type => $instanecOfType) {
               $class = 'icms_data_comment_' . $type;
               $this->assertTrue(class_exists($class, true), $class . " class doesn't exist");
               
               $instance = $this->getInstanceWithoutConstructor($class);
               $this->assertInternalType('object', $instance, $class. " is not an object");
               $this->assertTrue($instance instanceof $instanecOfType, $class . ' doesn\'t extend ' . $instanecOfType);
        }
        $this->assertTrue(class_exists('icms_data_comment_Renderer', true), "icms_data_comment_Renderer class doesn't exist");        
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
            'icms_data_comment_Handler' => [
                'getList',
                'getByItemId',
                'getCountByItemId',
                'getTopComments',
                'getThread',
                'updateByField',
                'deleteByModule'
            ],
            'icms_data_comment_Object' => [
                'isRoot'
            ]
        ] as $class => $methods) {
            $instance = $this->getInstanceWithoutConstructor($class);
            foreach ($methods as $method) {
                $this->assertTrue(method_exists($instance, $method), $method . ' doesn\'t exists for ' . $class);
            }
        }
    }  
    
    /**
     * Test comments renderer
     */
    public function testCommentRenderer() {
        $this->assertTrue(method_exists('icms_data_comment_Renderer', 'instance'), "icms_data_comment_Renderer doesn\'t have static method 'instance'");
        $tpl = null;
        $instance = \icms_data_comment_Renderer::instance($tpl);
        $this->assertTrue($instance instanceof \icms_data_comment_Renderer, 'instance() returns object that is not \icms_data_comment_Renderer');
        foreach (['setComments', 'renderFlatView', 'renderThreadView', 'renderNestView'] as $method) {
            $this->assertTrue(method_exists($instance, $method), $method . ' doesn\'t exists for icms_data_comment_Renderer');
        }
    }
    
}