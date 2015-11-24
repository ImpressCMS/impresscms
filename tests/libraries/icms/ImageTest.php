<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

class ImageTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * Test if is available
     */
    public function testAvailability() {
        foreach ([
                'icms_image_category_Handler' => 'icms_ipf_Handler',
                'icms_image_category_Object' => 'icms_ipf_Object',
                'icms_image_set_Handler' => 'icms_ipf_Handler',
                'icms_image_set_Object' => 'icms_ipf_Object',
                'icms_image_Handler' => 'icms_ipf_Handler',
                'icms_image_Object' => 'icms_ipf_Object'
            ] as $class => $must_be_instance_of) {
                $this->assertTrue(class_exists($class, true), $class . " class doesn't exist");        
            if ($must_be_instance_of !== null) {
                $instance = $this->getMockBuilder($class)
                    ->disableOriginalConstructor()
                    ->getMock();
                $this->assertTrue( $instance instanceof $must_be_instance_of, $class . " is not instanceof " . $must_be_instance_of);
            }
        }
    }  
    
}