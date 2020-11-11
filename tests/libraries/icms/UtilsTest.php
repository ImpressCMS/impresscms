<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

use icms_Utils;
use PHPUnit\Framework\TestCase;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class UtilsTest extends TestCase {

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
        $this->assertIsArray( icms_Utils::mimetypes(), 'mimetypes must return array');
    }

}