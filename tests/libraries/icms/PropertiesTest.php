<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

class PropertiesTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * Does icms_properties_Handler exists and it's usable?
     */
    public function testExists() {
        $this->assertTrue(class_exists('icms_properties_Handler', false), 'icms_properties_Handler class doesn exist');
        $mock = $this->getMockForAbstractClass('icms_properties_Handler');
        $this->assertTrue($mock instanceof \icms_properties_Handler, 'Can\'t extend icms_properties_Handler with class');
    }              
    
    /**
     * Tests that all needed public methods exists
     */
    public function testNeededPublicMethods() {
        $mock = $this->getMockForAbstractClass('icms_properties_Handler');
        foreach ([
                'getVar' => null, 
                'setVar' => null, 
                'assignVar'  => null, 
                'assignVars' => null, 
                'getChangedVars' => 'array', 
                'getDefaultVars' => 'array', 
                'getProblematicVars' => 'array',
                'getValues' => 'array',
                'getVarForDisplay' => null,
                'getVarForEdit' => null,
                'getVarForForm' => null,
                'getVarInfo' => null,
                'getVarNames' => null,
                'getVars' => 'array',
                'isChanged' => 'bool',
                'serialize' => 'string',
                'setVarInfo' => null,
                'toArray' => 'array',
                'unserialize' => null
            ] as $method => $retType) {
            $this->assertTrue(method_exists($mock, $method), 'No public ' . $method . ' method');
            if ($retType !== null) {
                $this->assertInternalType($retType, $mock->$method(), "$method returns wrong data type");
            }
        }
    }

    /**
     * Tests if initVars works
     */
    public function testInitVars() {
        $mock = $this->getMockForAbstractClass('icms_properties_Handler');
        
        $reflection_method = new \ReflectionMethod($mock, 'initVar');
        $this->assertTrue(is_object($reflection_method), 'initVar method doesn\'t exists');
        $this->assertTrue($reflection_method->isProtected(), 'initVar method doesn\'t is protected');
        
        $reflection_method->setAccessible(true);
        
        $this->assertCount(0, $mock->getVars(), 'Properties creates object with existing vars. This must not be possible for new objects.');
        
        $reflection_method->invoke($mock, 'var_array', \icms_properties_Handler::DTYPE_ARRAY, array(), false);        
        
        $vars = $mock->getVars();
        $this->assertCount(1, $vars, 'Couln\'t init var');
        $this->assertArrayHasKey('var_array', $vars, 'Couln\'t init var');
        
        $this->assertTrue(isset($mock->var_array), 'Can\'t use fast property access (withou calling function)');                
        
        $this->assertInternalType('array', $mock->getVar('var_array'), 'When tried to read just cread var wrong data returned');
        $this->assertInternalType('array', $mock->var_array, 'When tried to read just cread var wrong data returned');
    }            
    
    /**
     * Tests file data type
     */
    public function testTypeFile() {
        $mock = $this->createMockWithInitVar('v', \icms_properties_Handler::DTYPE_FILE, null, [
            \icms_properties_Handler::VARCFG_PATH => sys_get_temp_dir(),
            \icms_properties_Handler::VARCFG_PREFIX => crc32(microtime(true))
        ]);
        
        $this->assertInternalType('null', $mock->v, 'DTYPE_FILE must have null uncoverted');
        
        $mock->v = 'data:image/gif;base64,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';
        
        $this->assertInternalType('array', $mock->v, 'DTYPE_FILE must be returned as array un success');
        $this->assertArrayHasKey('filename', $mock->v, 'Filename key on FILE type isn\'t returned');
        $this->assertArrayHasKey('mimetype', $mock->v, 'Mimetype key on FILE type isn\'t returned');
    }
    
    /**
     * Tests datetime data type
     */
    public function testTypeDateTime() {
        $mock = $this->createMockWithInitVar('v', \icms_properties_Handler::DTYPE_DATETIME, null, false);
            
        $this->assertInternalType('null', $mock->v, 'DTYPE_DATETIME must have null uncoverted');
        
        foreach ([[52], [59 => 'aaa'], true, 1, 1.0, -9, 'test', [], new \stdClass(), function () {}] as $v) {
            $mock->v = $v;
            $this->assertTrue(is_int($mock->v) || (is_object($mock->v) && $mock->v instanceof \DateTime), 'DTYPE_DATETIME must convert all data');
        }
    }    
    
    /**
     * Tests int data type
     */
    public function testTypeInt() {
        $this->doDefaultDataTypeTest('DTYPE_INTEGER', \icms_properties_Handler::DTYPE_INTEGER, 'int');
    }
    
    /**
     * Tests float data type
     */
    public function testTypeFloat() {
        $this->doDefaultDataTypeTest('DTYPE_FLOAT', \icms_properties_Handler::DTYPE_FLOAT, 'float');
    }
    
    /**
     * Tests bool data type
     */
    public function testTypeBool() {
        $this->doDefaultDataTypeTest('DTYPE_BOOLEAN', \icms_properties_Handler::DTYPE_BOOLEAN, 'bool');
    }
    
    /**
     * Tests string data type
     */
    public function testTypeString() {
        $this->doDefaultDataTypeTest('DTYPE_STRING', \icms_properties_Handler::DTYPE_STRING, 'string');
    }
    
    /**
     * Tests object data type
     */
    public function testTypeObject() {
        $this->doDefaultDataTypeTest('DTYPE_OBJECT', \icms_properties_Handler::DTYPE_OBJECT, 'object');
    }    
    
    /**
     * Do default data type test
     * 
     * @param string $name
     * @param string $dtype
     * @param string $itype
     */
    private function doDefaultDataTypeTest($name, $dtype, $itype) {
        $mock = $this->createMockWithInitVar('v', $dtype, null, false);
        
        $this->assertInternalType('null', $mock->v, $name . ' must have null uncoverted');
        
        foreach ([[52], [59 => 'aaa'], true, 1, 1.0, -9, 'test', [], new \stdClass(), function () {}] as $v) {
            $mock->v = $v;
            $this->assertInternalType($itype, $mock->v, $name . ' must convert all data to ' . $itype);
        }
    }
    
    /**
     * Tests Array data type
     */
    public function testTypeArray() {
        $mock = $this->createMockWithInitVar('v', \icms_properties_Handler::DTYPE_ARRAY, null, false);
            
        $this->assertInternalType('null', $mock->v, 'DTYPE_ARRAY must have null uncoverted');
        
        foreach ([[52], [59 => 'aaa'], true, 1, 1.0, -9, 'test', [], new \stdClass(), function () {}] as $v) {
            $mock->v = $v;
            $this->assertInternalType('array', $mock->v, 'DTYPE_ARRAY must convert all data');
            if (is_array($v)) {
                $this->assertSame($v, $mock->v, 'Array must be unchanged');
            } else {
                $this->assertSame([$v], array_values($mock->v), 'Simple values must be converted as array values without modifications');
            }
        }
    }
    
    /**
     * Create new mock object with initialized var
     * 
     * @param string $key
     * @param string $dataType
     * @param mixed $defaultValue
     * @param bool $required
     * @param null|array $otherCfg
     * 
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createMockWithInitVar($key, $dataType, $defaultValue = null, $required = false, $otherCfg = null) {
        $mock = $this->getMockForAbstractClass('icms_properties_Handler');
        $reflection_method = new \ReflectionMethod($mock, 'initVar');
        $reflection_method->setAccessible(true);
        $reflection_method->invoke($mock, $key, $dataType, $defaultValue, $required, $otherCfg);
        
        return $mock;
    }
        
}