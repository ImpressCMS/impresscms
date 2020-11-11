<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

use icms_core_Versionchecker;
use PHPUnit\Framework\TestCase;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class VersionCheckerTest extends TestCase {

    /**
     * Test if is available
     */
    public function testAvailability() {
        $this->assertTrue(class_exists('icms_core_Versionchecker', true), "icms_core_Versionchecker class doesn't exist");
    }

    /**
     * Tests getInstance method
     */
    public function testGetInstance() {
        $this->assertTrue(method_exists('icms_core_Versionchecker', 'getInstance'), 'static method getInstance doesn\'t exists for icms_core_Versionchecker');
        $this->assertInstanceOf(icms_core_Versionchecker::class, icms_core_Versionchecker::getInstance(), 'service method doesn\'t return instanceod icms_core_Versionchecker type');
    }

    /**
     * Checks if all required methods are available
     */
    public function testMethodsAvailability() {
        $instance = icms_core_Versionchecker::getInstance();
        foreach ([ 'check', 'getErrors' ] as $method) {
            $this->assertTrue(method_exists($instance, $method), $method . ' doesn\'t exists');
        }
    }

    /**
     * Checks if all required variables are available
     */
    public function testVariables() {
        $instance = icms_core_Versionchecker::getInstance();
        $this->assertIsArray( $instance->errors, '$errors must be array');
        $this->$this->assertIsString( $instance->latest_version_name, '$latest_version_name must be string');
        $this->$this->assertIsString( $instance->installed_version_name, '$installed_version_name must be string');
        $this->$this->assertIsString( $instance->latest_url, '$latest_url must be string');
        $this->$this->assertIsString( $instance->latest_changelog, '$latest_changelog must be string');
    }

}