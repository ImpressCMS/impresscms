<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

class ControllerTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * Tests all needed public methods
     */
    public function testHandlerMethods() {
        $obj = new \icms_controller_Handler();
        foreach (['makeURL', 'get', 'parseParamsStringToArray', 'exec', 'getControllersPath', 'getAvailable'] as $method) {
            $this->assertTrue(method_exists($obj, $method), 'No public ' . $method . ' method');
        }
    }
    
    /**
     * Checks if type is correct one
     */
    public function testType() {
        $obj = new \icms_controller_Handler();
        switch (PHP_SAPI) {
            case 'embed':
                $this->assertSame('embed', $obj->type, 'Bad type detected');
            break;
            case 'cli':
                $this->assertSame('command', $obj->type, 'Bad type detected');
            break;
            default:
                $this->assertSame('controller', $obj->type, 'Bad type detected');
            break;
        }        
    }
    
    /**
     * Checks if PARAMS_FORMAT is available and is string
     */
    public function testObjectAvailability() {
         $mock = $this->getMockForAbstractClass('icms_controller_Object');
         $class = new \ReflectionClass($mock);
         
         $this->assertInternalType('string', $class->getConstant('PARAMS_FORMAT'), 'PARAMS_FORMAT constant must be defined as string');
    }
    
}