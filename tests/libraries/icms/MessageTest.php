<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

use PHPUnit\Framework\TestCase;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class MessageTest extends TestCase {

    /**
     * Tests availability
     */
    public function testAvailability() {
        $this->assertTrue(class_exists('icms_core_Message', true), "icms_core_Message class doesn't exist");
        foreach ([ 'warning', 'error', 'result', 'confirm' ] as $method) {
             $this->assertTrue(method_exists('icms_core_Message', $method), $method . ' doesn\'t exists');
        }
    }

}