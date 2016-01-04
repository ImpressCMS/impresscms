<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class ResponseTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * Tests Text response type
     */
    public function testTextResponse() {
        $this->doDefaultResponseTypeTest('Text');
    }
    
    /**
     * Is HTTP_* constants for class defined correctly
     */
    public function testHTTPConstantsAvailability() {
        $response = new \icms_response_Text();
        
        $class = new \ReflectionClass($response);
        $constants = $class->getConstants();
        
        $this->assertNotEmpty($constants, 'Class doesn\'t have any constants');
        
        foreach ($constants as $name => $value) {
            if (substr($name, 0, 5) != 'HTTP_') {
                continue;
            }
            $this->assertInternalType('int', $value, $name . ' constant value must be defined as int');
        }        
    }
    
    /**
     * Tests HTML response type
     */     
    public function testHTML() {
        $this->doDefaultResponseTypeTest('HTML');
    }    
        
    /**
     * Tests JSON response type
     */     
    public function testJSON() {
        $this->doDefaultResponseTypeTest('JSON');
    }        
    
    /**
     * Tests JSONP response type
     */     
    public function testJSONP() {
        $this->doDefaultResponseTypeTest('JSONP');
    }        
    
    /**
     * Tests DefaultEmptyPage response type
     */     
    public function testDefaultEmptyPage() {
        $this->doDefaultResponseTypeTest('DefaultEmptyPage');
    }
    
    /**
     * Tests Serialize response type
     */     
    public function testSerialize() {
        $this->doDefaultResponseTypeTest('Serialize');
    }      
    
    /**
     * Tests Events response type
     */     
    public function testEvents() {
        $this->doDefaultResponseTypeTest('Events');
    }
    
    /**
     * Do default type
     * 
     * @param string $type
     */
    private function doDefaultResponseTypeTest($type) {
        $class = 'icms_response_' . $type;
        $this->assertTrue(class_exists($class, true), $class . ' class doesn exist');
        
        $instance = new \ReflectionClass($class);
        $this->assertInternalType('string', $instance->getConstant('CONTENT_TYPE'), 'CONTENT_TYPE constant must be defined as string');
    }
    
}