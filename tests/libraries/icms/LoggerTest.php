<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class LoggerTest extends \PHPUnit_Framework_TestCase {

    /**
     * Test if icms_core_DataFilter is available
     */
    public function testAvailability(): void
	{
        $this->assertTrue(class_exists('icms_core_Logger', true), "icms_core_Logger class doesn't exist");
    }

    /**
     * Tests how renders
     */
    public function testRender(): void
	{
        $logger = &\icms_core_Logger::instance();
        $logger->disableRendering();
        $this->assertInternalType('string', $logger->render('a'), 'Render must return string');
        $this->assertInternalType('string', $logger->dump(), 'dump must return string');
		$this->assertInternalType('numeric', $logger->dumpTime(), 'dumpTime must return numeric');
    }

}