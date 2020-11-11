<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

use icms_core_Logger;
use PHPUnit\Framework\TestCase;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class LoggerTest extends TestCase {

    /**
     * Test if icms_core_DataFilter is available
     */
    public function testAvailability(): void
	{
        $this->assertTrue(class_exists('icms_core_Logger', true), "icms_core_Logger class doesn't exist");
    }

    /**
     * Tests timer
     */
    public function testTimer(): void
	{
        $logger = &icms_core_Logger::instance();
        $logger->disableRendering();
        $timer = sha1(microtime(true));
        $count = count($logger->timers);
        $this->assertCount($count, $logger->timers, 'Timer array should be not increased by one');
		$logger->startTime($timer);
        $this->assertCount($count + 1, $logger->timers, 'Timer array should be increased by one');
        $logger->stopTime($timer);
        $this->assertCount($count + 1, $logger->timers, 'Timer array should be increased by one');
    }

    /**
     * Tests how renders
     */
    public function testRender(): void
	{
        $logger = &icms_core_Logger::instance();
        $logger->disableRendering();
        $this->$this->assertIsString( $logger->render('a'), 'Render must return string');
        $this->$this->assertIsString( $logger->dump(), 'dump must return string');
		$this->assertIsNumeric( $logger->dumpTime(), 'dumpTime must return numeric');
    }

}