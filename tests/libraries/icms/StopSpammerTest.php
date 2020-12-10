<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

use icms_core_StopSpammer;
use PHPUnit\Framework\TestCase;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class StopSpammerTest extends TestCase {

    /**
     * Test if icms_core_DataFilter is available
     */
    public function testAvailability() {
        $this->assertTrue(class_exists('icms_core_StopSpammer', true), "icms_core_StopSpammer class doesn't exist");
    }

    /**
     * Checks if all required methods are available
     */
    public function testMethodsAvailability() {
        $instance = new icms_core_StopSpammer();
        foreach ([ 'checkForField', 'badUsername', 'badEmail', 'badIP' ] as $method) {
            $this->assertTrue(method_exists($instance, $method), $method . ' doesn\'t exists');
        }
    }

}