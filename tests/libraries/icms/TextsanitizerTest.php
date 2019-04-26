<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class TextsanitizerTest extends \PHPUnit_Framework_TestCase {

    /**
     * Test if is available
     */
    public function testAvailability() {
        $this->assertTrue(class_exists('icms_core_Textsanitizer', true), "icms_core_Textsanitizer class doesn't exist");
    }

    /**
     * Tests getInstance method
     */
    public function testGetInstance() {
        $this->assertTrue(method_exists('icms_core_Textsanitizer', 'getInstance'), 'static method getInstance doesn\'t exists for icms_core_Textsanitizer');
        $this->assertTrue(\icms_core_Textsanitizer::getInstance() instanceof \icms_core_Textsanitizer, 'service method doesn\'t return instanceod icms_core_Textsanitizer type');
    }

    /**
     * Checks if all required methods are available
     */
    public function testMethodsAvailability() {
        $instance = \icms_core_Textsanitizer::getInstance();
        foreach ([ '_filterImgUrl', 'checkUrlString', 'displayTarea', 'previewTarea' ] as $method) {
            $this->assertTrue(method_exists($instance, $method), $method . ' doesn\'t exists');
        }
    }

    /**
     * Checks if all required variables are available
     */
    public function testVariables() {
        $instance = \icms_core_Textsanitizer::getInstance();
        $this->assertInternalType('array', $instance->displaySmileys, 'displaySmileys must be array');
        $this->assertInternalType('array', $instance->allSmileys, 'allSmileys must be array');
    }

}