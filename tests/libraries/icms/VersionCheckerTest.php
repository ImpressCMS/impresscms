<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

class VersionCheckerTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * Test if icms_core_DataFilter is available
     */
    public function testAvailability() {
        $this->assertTrue(class_exists('icms_core_Versionchecker', true), "icms_core_Versionchecker class doesn't exist");        
    }
    
    /**
     * Tests getInstance method
     */
    public function testGetInstance() {
        $this->assertTrue(method_exists('icms_core_Versionchecker', 'getInstance'), 'static method getInstance doesn\'t exists for icms_core_Versionchecker');
        $this->assertTrue(\icms_core_Versionchecker::getInstance() instanceof \icms_core_Versionchecker, 'service method doesn\'t return instanceod icms_core_Versionchecker type');
    }    
    
    /**
     * Checks if all required methods are available
     */
    public function testMethodsAvailability() {
        $instance = \icms_core_Versionchecker::getInstance();
        foreach ([ 'check', 'getErrors' ] as $method) {
            $this->assertTrue(method_exists($instance, $method), $method . ' doesn\'t exists');
        }
    }
    
    /**
     * Checks if all required variables are available
     */
    public function testVariables() {
        $instance = \icms_core_Versionchecker::getInstance();
        $this->assertInternalType('array', $instance->errors, '$errors must be array');
        $this->assertInternalType('string', $instance->version_xml, '$version_xml must be string');
        $this->assertInternalType('string', $instance->latest_version_name, '$latest_version_name must be string');
        $this->assertInternalType('string', $instance->installed_version_name, '$installed_version_name must be string');
        $this->assertInternalType('int', $instance->latest_build, '$latest_build must be int');
        $this->assertInternalType('int', $instance->latest_status, '$latest_status must be int');
        $this->assertInternalType('string', $instance->latest_url, '$latest_url must be string');
        $this->assertInternalType('string', $instance->latest_changelog, '$latest_changelog must be string');
    }       
    
}