<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

$_SERVER['REMOTE_ADDR'] = '0.0.0.0';
require_once dirname(dirname(dirname(__DIR__))) . '/htdocs/mainfile.php';

class Properties extends \PHPUnit_Framework_TestCase {
    
    public function testExtends() {
        $this->assertTrue(class_exists('icms_properties_Handler', false), 'icms_properties_Handler class doesn exist');
        $this->assertTrue($this->createTempInstance() instanceof \icms_properties_Handler, 'Can\'t extend icms_properties_Handler with class');
    }
    
    /**
     * Returns temp instance of class
     * 
     * @return \icms_properties_Handler
     */
    private function createTempInstance() {      
        if (!class_exists('tmp_properties')) {
            eval('namespace ImpressCMS\Tests\Libraries\ICMS\tmp; class properties extends \icms_properties_Handler {}');
        }
        return new tmp\properties();
    }
        
}