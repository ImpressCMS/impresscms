<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

use DateTime;
use ImpressCMS\Core\Properties\AbstractProperties;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use ReflectionException;
use ReflectionMethod;
use stdClass;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class PropertiesTest extends TestCase {

    /**
     * Does AbstractProperties exists and it's usable?
     */
    public function testExists() {
        $this->assertTrue(class_exists('\\ImpressCMS\\Core\\Properties\\AbstractProperties', false), 'AbstractProperties class doesn exist');
        $mock = $this->getMockForAbstractClass('\\ImpressCMS\\Core\\Properties\\AbstractProperties');
        $this->assertInstanceOf(AbstractProperties::class, $mock, 'Can\'t extend AbstractProperties with class');
    }

    /**
     * Tests that all needed public methods exists
     */
    public function testNeededPublicMethods() {
        $mock = $this->getMockForAbstractClass('\\ImpressCMS\\Core\\Properties\\AbstractProperties');
        foreach ([
                'getVar' => null,
                'setVar' => null,
                'assignVar'  => null,
                'assignVars' => null,
                'getChangedVars' => 'assertIsArray',
                'getDefaultVars' => 'assertIsArray',
                'getProblematicVars' => 'assertIsArray',
                'getValues' => 'assertIsArray',
                'getVarForDisplay' => null,
                'getVarForEdit' => null,
                'getVarForForm' => null,
                'getVarInfo' => null,
                'getVarNames' => null,
                'getVars' => 'assertIsArray',
                'isChanged' => 'bool',
                'serialize' => 'assertIsString',
                'setVarInfo' => null,
                'toArray' => 'assertIsArray',
                'unserialize' => null
            ] as $method => $func) {
            $this->assertTrue(method_exists($mock, $method), 'No public ' . $method . ' method');
            if ($func !== null) {
                $this->$func($mock->$method(), "$method returns wrong data type");
            }
        }
    }

	/**
	 * Tests if initVars works
	 * @throws ReflectionException
	 */
    public function testInitVars() {
        $mock = $this->getMockForAbstractClass('\\ImpressCMS\\Core\\Properties\\AbstractProperties');

        $reflection_method = new ReflectionMethod($mock, 'initVar');
        $this->assertIsObject( $reflection_method, 'initVar method doesn\'t exists');
        $this->assertTrue($reflection_method->isProtected(), 'initVar method doesn\'t is protected');

        $reflection_method->setAccessible(true);

        $this->assertCount(0, $mock->getVars(), 'Properties creates object with existing vars. This must not be possible for new objects.');

        $reflection_method->invoke($mock, 'var_array', AbstractProperties::DTYPE_ARRAY, array(), false);

        $vars = $mock->getVars();
        $this->assertCount(1, $vars, 'Couln\'t init var');
        $this->assertArrayHasKey('var_array', $vars, 'Couln\'t init var');

        $this->assertTrue(isset($mock->var_array), 'Can\'t use fast property access (withou calling function)');

        $this->$this->assertIsArray( $mock->getVar('var_array'), 'When tried to read just cread var wrong data returned');
        $this->$this->assertIsArray( $mock->var_array, 'When tried to read just cread var wrong data returned');
    }

    /**
     * Tests file data type
     */
    public function testTypeFile() {
        $mock = $this->createMockWithInitVar('v', AbstractProperties::DTYPE_FILE, null, false, [
			AbstractProperties::VARCFG_PATH => sys_get_temp_dir(),
			AbstractProperties::VARCFG_PREFIX => crc32(microtime(true)),
			AbstractProperties::VARCFG_ALLOWED_MIMETYPES => [
				'image/gif'
			]
        ]);

        $this->assertNull( $mock->v, 'DTYPE_FILE must have null uncoverted');

        $mock->v = 'data:image/gif;charset=utf-8;base64,R0lGODlhUABaAPcAAPz8/Pv7+/j4+Pf39/T09PPz8/Ly8vHx8fPy7vDw8O7u7vPt3e3t7ezs7Pnryerq6unp6fTozPToyujo6Ofn5+bm5uXl5fXlv/XkvOTk5OPj4+Li4uHh4d7e3vjdnvTcpN3d3dzc3PPcpvfZlNnZ2fTYl9jY2PPWk9fX1/TVjNXV1dTU1NPT09TT09LS0tHR0dHQ0NDQ0PbObs/Pz/bNa87OzvTMbM3NzfXLZczMzPPKZvbKYMrKyvPJY8nJycjIyMfHx8bGxsXFxcTExPTDTsPDw/TBSPPBSvTARcLCwvO/RPS/QsDAwL6+vr29vfO7NvS7NPS6Mby8vLu7u/S5LvO5MLm5ufO3KPO1Ire3t/O0H7W1tfKxFrOzs/OxFPOvDrGxsbCwsK+vr/OtCK6urvOsA62traurq6qqqqmpqaampqSkpKKioqCgoJ+fn56enp2dnZubm5qampmZmZKSkpCQkI6OjouLi4iIiIWFhYODg4KCgoGBgX9/f35+fn19fXx8fHp6enl5eXh4eHd3d3Z2dnV1dXR0dHNzc29vb21tbWlpaWhoaGdnZ2RkZGFhYWBgYF9fX1tbW1lZWVhYWFdXV1VVVVRUVFNTU1JSUlFRUVBQUE5OTv///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEHAJ0ALAAAAABQAFoAAAj/ADsJHEiwoMGDCBMqXMiwocOHECM+BEARQICLGDNq3Mixo8ePASoCcFhRgMkBKFOqXMmypcuXMFNmXEjRJIGbOHPq3Mmzp8+fOlVeTEhxwM0GFZIqXcq0qdOnUKMybWBUwFCDRQkglci1a8ENBgwMuDqwpoAGBryq9YrWgICRBLM2WEuXawWUZDsBMFmhrt+IVAfAFbhXAIm/iBveFRzX5OHEkBFuQDm4UwCUjyNrHriBwFiClwdk3qy58+eBoUcb9LADipcyZbxA2eGBtEPTAUBjPugAxxfYwIOX+YLDgW2FuHWLNkhDuHPhMo5L9pwb9W6CRJ5rB05EusHk1pcP/xyxvXyZEd4JgheYmmAU89ujpOdMXfnoMfC1f5kvcL3l6wJdkd9zVPDXiX/tDZTCgM6l0BAKL6igQkMqOLHFChAchCCAAhnBIHcCZdHFiDMQlAYjnKSoIiFZIDQHJSliMsgbPiTwVX3hqdaJDh/2IBATKnJCx0CPBGkkJ4oUlMMkQWISiBtB9KUejuxxOBAGSMC3xAUDVRLkkDUweaSRjwwkRSZGZiIIlBbceFqV4iH0gQ1cAIeFEjqUQNALYqo4pCRjjjlIJy+gmeYgbgjR5pRv/hdnQlWUoYUEB81wyZF2GBJooHNEMqYmhLwxxKL0NZpgQiKU8cQCBADRxyNlCv8UxpiGbmqrkZsU8kYRGbhZHZw6EhTBEScg8IAeKsbayay3LpKIJbcGKQkfeHhqiKik9kelo8Ea9IOnyQ4khq1w7AnIrZmgQRAYgrQRpa/2MRTHkcqOG2gSB527aRMGDcHGDxPAm+NCbowJyUBkBLoHQjdsegdCVuTwgMDALjTvkQcLlPCYViQE7ZguIBRCDAxQzO1CchiMcKAoJNTImJEotIKNjP56skIpY7zymAodMmYhCpmQVs3x4qyyQGYEqhAiY/qhUAcEmHwqQjkbmXEnSfOcENNHOp0QB99tO/VBVQd5ddZHLt20QhqEbaqVBpWt4tlKb712Qtlq+/ajB83/cTTWdSPEtZF/KJT3gWLDXZDfOiMd+EGDB1l4QlISPbBCjFs9ENpGqn3k5AhR4LbNYxuUudmbP25Q5CqCfpDoUitO0Olzp6614GO6bhDslleM+d+cB+k54QpVXirpsg9Ee4p03w557oaPXnRCy3PSfNp2fx597HybDrzqBbGeou4Ftc19t8p/7/zq0H8t/eXUq4897tonBELUvd8cf+OArx9++wghwQHOt5DqXa9z2SNeQlSgAAL+jn/BU9HwJKeQGMwlf6VbnPwQSD8FIoQHvNMb8rpXkCnY4YQotMMcBgKEFKZQIWtw4QnPoBASXPB40zPQWjZEQh1KhIfdMkAS/wAAAxIEAF8kgEEnkiCADSixCASgQBA6AQQGMAAInWgBBQowhE7AACz4KiIBwgiDIwIgiUscmoYS18MmAqABDQDABjqRlAO9sS8bCEAD5jiZAcyxAp45TAXc8scGGKYTSJEjIudCggEkBIg+pAskI6mWSVKyK5a85A/ZiD5NLiSTnnwIKAdCgA6QhgAsyFAn8LfKgbCABY/kJELUEIjNSEFFUaulQGpJgESkSArT2Vu3aNkJCJQyQx3IUCk7YMpiQoCZxYSlQKDZCWiygBKmVCUnsNgBTnQCDIkoZiyFOctaBiIQvsyDL1mgBk4EghJ16AQ6AwGGOCTCEY4gQB3uKf+FfTqin/EcSDfjOS8WSIESwBznCIdpTjV0IhEOhSgxWcCJDgQinixwxCspAQZKqCFqHs2lQ830zk44og5g6EQcKOGIZr7PdwYhZiAcOlN5qoGYneAEC2qqBkqcMxBAAIIjKMECoRI1DwEVyE0dQUs1xEEgvcxDMBdaTptadabEhAAnCFDTg0btq53IQyCiJtZbApMFXJUCGDih1lpmKA5SXSM5DyJTmtq1ne8EZk176dNaOoKff/2nPFOETY0SgBKduOYtOdHSqeaQIBBAZtSgVk0C0DKV02TlK6GKWVSqsprSlOZXTdkBaToWfhDBaSRH2ZDIXpK1ofzktgrTydh4rvEtjTmkbR+ymMoUxni7XUhgfAsAowA3uBrCi830YhI4IpdyN8FtQYoyAAPM8bk3IoBVlkuYwpwFKVIJr3jH6xS0VCUv0/UuTsLC3va6973wja9854sTmXA3vWYRQEz2y9/+tmQmDRGJRUBC4AIbOCMiwa6CdxsQADs=';

		$this->assertNull( $mock->v, 'DTYPE_FILE is null if there was access denied for uplaoing file');

        //$this->assertArrayHasKey('filename', $mock->v, 'Filename key on FILE type isn\'t returned');
        //$this->assertArrayHasKey('mimetype', $mock->v, 'Mimetype key on FILE type isn\'t returned');
    }

    /**
     * Tests datetime data type
     */
    public function testTypeDateTime() {
        $mock = $this->createMockWithInitVar('v', AbstractProperties::DTYPE_DATETIME, null, false);

		$this->assertNull( $mock->v, 'DTYPE_DATETIME must have null uncoverted');

        foreach ([[52], [59 => 'aaa'], true, 1, 1.0, -9, 'test', [], new stdClass(), function () {}] as $v) {
            $mock->v = $v;
            $this->assertTrue(is_int($mock->v) || (is_object($mock->v) && $mock->v instanceof DateTime), 'DTYPE_DATETIME must convert all data');
        }
    }

    /**
     * Tests int data type
     */
    public function testTypeInt() {
        $this->doDefaultDataTypeTest('DTYPE_INTEGER', AbstractProperties::DTYPE_INTEGER, 'int');
    }

    /**
     * Tests float data type
     */
    public function testTypeFloat() {
        $this->doDefaultDataTypeTest('DTYPE_FLOAT', AbstractProperties::DTYPE_FLOAT, 'float');
    }

    /**
     * Tests bool data type
     */
    public function testTypeBool() {
        $this->doDefaultDataTypeTest('DTYPE_BOOLEAN', AbstractProperties::DTYPE_BOOLEAN, 'bool');
    }

    /**
     * Tests string data type
     */
    public function testTypeString() {
        $this->doDefaultDataTypeTest('DTYPE_STRING', AbstractProperties::DTYPE_STRING, 'string');
    }

    /**
     * Tests object data type
     */
    public function testTypeObject() {
        $this->doDefaultDataTypeTest('DTYPE_OBJECT', AbstractProperties::DTYPE_OBJECT, 'object');
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

		$this->assertNull( $mock->v, $name . ' must have null uncoverted');

        foreach ([[52], [59 => 'aaa'], true, 1, 1.0, -9, 'test', []] as $v) {
            $mock->v = $v;
			if ($mock->v === null) {
				continue;
			}
            $this->assertInternalType($itype, $mock->v, $name . ' must convert all data to ' . $itype);
        }
    }

    /**
     * Tests Array data type
     */
    public function testTypeArray() {
        $mock = $this->createMockWithInitVar('v', AbstractProperties::DTYPE_ARRAY, null, false);

		$this->assertNull( $mock->v, 'DTYPE_ARRAY must have null uncoverted');

        foreach ([[52], [59 => 'aaa'], true, 1, 1.0, -9, 'test', [], new stdClass(), function () {}] as $v) {
            $mock->v = $v;
            $this->$this->assertIsArray( $mock->v, 'DTYPE_ARRAY must convert all data');
            if (is_array($v)) {
                $this->assertSame($v, $mock->v, 'Array must be unchanged');
            } else {
                $this->assertSame((array)$v, array_values($mock->v), 'Simple values must be converted as array values without modifications');
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
	 * @return PHPUnit_Framework_MockObject_MockObject
	 * @throws ReflectionException
	 */
    private function createMockWithInitVar($key, $dataType, $defaultValue = null, $required = false, $otherCfg = null) {
        $mock = $this->getMockForAbstractClass('\\ImpressCMS\\Core\\Properties\\AbstractProperties');
        $reflection_method = new ReflectionMethod($mock, 'initVar');
        $reflection_method->setAccessible(true);
        $reflection_method->invoke($mock, $key, $dataType, $defaultValue, $required, $otherCfg);

        return $mock;
    }

}
