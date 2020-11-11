<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

use icms_data_comment_Renderer;
use ImpressCMS\Core\Models\AbstractExtendedHandler;
use ImpressCMS\Core\Models\AbstractExtendedModel;
use PHPUnit\Framework\TestCase;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class CommentTest extends TestCase {

    /**
     * Test if icms_core_DataFilter is available
     */
    public function testAvailability() {
        foreach (['Handler' => AbstractExtendedHandler::class, 'Object' => AbstractExtendedModel::class] as $type => $instanecOfType) {
               $class = 'icms_data_comment_' . $type;
               $this->assertTrue(class_exists($class, true), $class . " class doesn't exist");

               $instance = $this->getInstanceWithoutConstructor($class);
               $this->assertIsObject($instance, $class. ' is not an object');
               $this->assertInstanceOf($instanecOfType, $instance, $class . ' doesn\'t extend ' . $instanecOfType);
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
        $instance = icms_data_comment_Renderer::instance($tpl);
        $this->assertInstanceOf(icms_data_comment_Renderer::class, $instance, 'instance() returns object that is not \icms_data_comment_Renderer');
        foreach (['setComments', 'renderFlatView', 'renderThreadView', 'renderNestView'] as $method) {
            $this->assertTrue(method_exists($instance, $method), $method . ' doesn\'t exists for icms_data_comment_Renderer');
        }
    }

}