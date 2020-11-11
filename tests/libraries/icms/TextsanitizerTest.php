<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

use icms_core_Textsanitizer;
use PHPUnit\Framework\TestCase;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class TextsanitizerTest extends TestCase {

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
        $this->assertInstanceOf(icms_core_Textsanitizer::class, icms_core_Textsanitizer::getInstance(), 'service method doesn\'t return instanceod icms_core_Textsanitizer type');
    }

    /**
     * Checks if all required methods are available
     */
    public function testMethodsAvailability() {
        $instance = icms_core_Textsanitizer::getInstance();
        foreach ([ '_filterImgUrl', 'checkUrlString', 'displayTarea', 'previewTarea' ] as $method) {
            $this->assertTrue(method_exists($instance, $method), $method . ' doesn\'t exists');
        }
    }

    /**
     * Checks if all required variables are available
     */
    public function testVariables() {
        $instance = icms_core_Textsanitizer::getInstance();
        $this->$this->assertIsArray( $instance->displaySmileys, 'displaySmileys must be array');
        $this->$this->assertIsArray( $instance->allSmileys, 'allSmileys must be array');
    }

}