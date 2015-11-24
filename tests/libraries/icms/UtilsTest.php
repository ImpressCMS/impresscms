<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

class UtilsTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * Test if is available
     */
    public function testAvailability() {
        $this->assertTrue(class_exists('icms_Utils', true), "icms_Utils class doesn't exist");        
    }
    
    /**
     * Test mimetypes functionality
     */
    public function testMimeTypes() {
        $this->assertTrue(method_exists('icms_Utils', 'mimetypes'), 'mimetypes method for icms_Utils doesn\'t exists');
        $this->assertInternalType('array', \icms_Utils::mimetypes(), 'mimetypes must return array');
    }
    
}