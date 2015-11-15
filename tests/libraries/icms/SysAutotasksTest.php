<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

class SysAutotasksTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * Test if is available
     */
    public function testAvailability() {
        $this->assertTrue(class_exists('icms_sys_autotasks_System', true), 'icms_sys_autotasks_System does\'t exist');
        $instance = $this->getClassInstance('icms_sys_autotasks_System');          
        $this->assertTrue($instance instanceof \icms_sys_autotasks_ISystem, 'icms_sys_autotasks_System doesn\'t implements icms_sys_autotasks_ISystem');
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
    
}