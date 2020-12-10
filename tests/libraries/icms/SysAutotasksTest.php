<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

use PHPUnit\Framework\TestCase;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class SysAutotasksTest extends TestCase {

    /**
     * Test if is available
     */
    public function testAvailability() {
        $this->assertTrue(class_exists('icms_sys_autotasks_System', true), 'icms_sys_autotasks_System does\'t exist');
    }

}