<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class SysAutotasksTest extends \PHPUnit_Framework_TestCase {

    /**
     * Test if is available
     */
    public function testAvailability() {
        $this->assertTrue(class_exists('icms_sys_autotasks_System', true), 'icms_sys_autotasks_System does\'t exist');
    }

}