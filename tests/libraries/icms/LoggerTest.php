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
    public function testAvailability() {
        $this->assertTrue(class_exists('icms_core_Logger', true), "icms_core_Logger class doesn't exist");
    }

    /**
     * Test public vars types
     */
    public function testPublicVars() {
        $logger = &\icms_core_Logger::instance();
        $this->assertInternalType('array', $logger->queries, '$queries must be an array');
        $this->assertInternalType('array', $logger->blocks, '$blocks must be an array');
        $this->assertInternalType('array', $logger->extra, '$extra must be an array');
        $this->assertInternalType('array', $logger->logstart, '$logstart must be an array');
        $this->assertInternalType('array', $logger->logend, '$logend must be an array');
        $this->assertInternalType('array', $logger->errors, '$errors must be an array');
        $this->assertInternalType('array', $logger->deprecated, '$deprecated must be an array');
        $this->assertInternalType('bool', $logger->usePopup, '$usePopup must be of booleam type');
        $this->assertInternalType('bool', $logger->activated, '$activated must be of booleam type');
    }


    /**
     * Tests timer
     */
    public function testTimer() {
        $logger = &\icms_core_Logger::instance();
        $logger->disableRendering();
        $timer = sha1(microtime(true));
        $count = count($logger->logstart);
        $count2 = count($logger->logend);
        $logger->startTime($timer);
        $this->assertCount($count2, $logger->logend, 'Timer array should be not increased by one');
        $this->assertCount($count + 1, $logger->logstart, 'Timer array should be increased by one');
        $logger->stopTime($timer);
        $this->assertCount($count2 + 1, $logger->logend, 'Timer array should be increased by one');
    }

    /**
     * Tests query logging
     */
    public function testQueryLogging() {
        $logger = &\icms_core_Logger::instance();
        $logger->disableRendering();
        $logger->activated = true;
        $count = count($logger->queries);
        $logger->addQuery('SELECT 1;');
        $this->assertCount($count + 1, $logger->queries, 'Queries array should be increased by one');
        $logger->activated = false;
        $logger->addQuery('SELECT 1;');
        $this->assertCount($count + 1, $logger->queries, 'Queries array should be unchanged');
    }

    /**
     * Test block logging
     */
    public function testBlockLogging() {
        $logger = &\icms_core_Logger::instance();
        $logger->disableRendering();
        $logger->activated = true;
        $count = count($logger->blocks);
        $logger->addBlock('test', true, 3600);
        $this->assertCount($count + 1, $logger->blocks, 'Blocks array should be increased by one');
        $logger->activated = false;
        $logger->addBlock('test', true, 3600);
        $this->assertCount($count + 1, $logger->blocks, 'Blocks array should be unchanged');
        $this->assertArrayHasKey('name', current($logger->blocks), 'Block log item doesn\'t have `name`');
        $this->assertArrayHasKey('cached', current($logger->blocks), 'Block log item doesn\'t have `cached`');
        $this->assertArrayHasKey('cachetime', current($logger->blocks), 'Block log item doesn\'t have `cachetime`');
    }

    /**
     * Test extra logging
     */
    public function testExtraLogging() {
        $logger = &\icms_core_Logger::instance();
        $logger->disableRendering();
        $logger->activated = true;
        $count = count($logger->extra);
        $logger->addExtra('test', mt_rand(0, 1000));
        $this->assertCount($count + 1, $logger->extra, 'Extra array should be increased by one');
        $logger->activated = false;
        $logger->addExtra('test', mt_rand(0, 1000));
        $this->assertCount($count + 1, $logger->extra, 'Extra array should be unchanged');
        $this->assertArrayHasKey('name', current($logger->extra), 'Extra log item doesn\'t have `name`');
        $this->assertArrayHasKey('msg', current($logger->extra), 'Extra log item doesn\'t have `msg`');
    }

    /**
     * Test deprecached logging
     */
    public function testDeprecachedLogging() {
        $logger = &\icms_core_Logger::instance();
        $logger->disableRendering();
        $logger->activated = true;
        $count = count($logger->deprecated);
        $logger->addDeprecated(sha1(time()));
        $this->assertCount($count + 1, $logger->deprecated, 'Deprecached array should be increased by one');
        $logger->activated = false;
        $logger->addDeprecated(sha1(time()));
        $this->assertCount($count + 1, $logger->deprecated, 'Deprecached array should be unchanged');
    }

    /**
     * Test error logging
     */
    public function testHandleErrorLogging() {
        $logger = &\icms_core_Logger::instance();
        $logger->disableRendering();
        $logger->activated = true;
        $count = count($logger->errors);
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        trigger_error('test', E_USER_NOTICE);
        $this->assertCount($count + 1, $logger->errors, 'Errors array should be increased by one');
        $logger->activated = false;
        trigger_error('test', E_USER_NOTICE);
        $this->assertCount($count + 1, $logger->errors, 'Errors array should be unchanged');
        $this->assertArrayHasKey('errno', end($logger->errors), 'Error log item doesn\'t have `errno`');
        $this->assertArrayHasKey('errstr', end($logger->errors), 'Error log item doesn\'t have `errstr`');
        $this->assertArrayHasKey('errfile', end($logger->errors), 'Error log item doesn\'t have `errfile`');
        $this->assertArrayHasKey('errline', end($logger->errors), 'Error log item doesn\'t have `errline`');
    }

    /**
     * Tests how renders
     */
    public function testRender() {
        $logger = &\icms_core_Logger::instance();
        $logger->disableRendering();
        $this->assertInternalType('string', $logger->render('a'), 'Render must return string');
        $this->assertInternalType('string', $logger->dump(), 'dump must return string');
        $this->assertInternalType('float', $logger->dumpTime(), 'dumpTime must return float');
    }

}