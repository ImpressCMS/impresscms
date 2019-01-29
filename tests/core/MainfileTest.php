<?php

namespace ImpressCMS\Tests\Core;

/**
 * Tests mainfile.php
 *
 * @package ImpressCMS\Tests\Core
 */
class MainfileTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * Tests if ICMS_MAINFILE_INCLUDED is defined correctly
	 */
	public function testIcmsMainfileIncludedConstantHandling() {
		$this->assertTrue(defined('ICMS_MAINFILE_INCLUDED'), 'ICMS_MAINFILE_INCLUDED is not defined after mainfile.php inclusion');
	}

	/**
	 * Tests path constant
	 */
	public function testPathConstant() {
		$this->assertTrue(defined('ICMS_ROOT_PATH'), 'ICMS_ROOT_PATH is not defined');
		$this->assertSame(ICMS_ROOT_PATH, dirname(dirname(__DIR__)), 'ICMS_ROOT_PATH has bad value');
	}

	/**
	 * Tests logging hook constant
	 */
	public function testLoggingHook() {
		$this->assertTrue(defined('ICMS_LOGGING_HOOK'), 'ICMS_LOGGING_HOOK is not defined');
		$this->assertSame(getenv('LOGGING_HOOK'), ICMS_LOGGING_HOOK, 'ICMS_LOGGING_HOOK is not same as LOGGING_HOOK env variable');
	}

	/**
	 * Tests default group constants
	 */
	public function testDefaultGroupConstants() {
		$this->assertTrue(defined('ICMS_GROUP_ADMIN'), 'ICMS_GROUP_ADMIN is not defined');
		$this->assertTrue(defined('ICMS_GROUP_USERS'), 'ICMS_GROUP_USERS is not defined');
		$this->assertTrue(defined('ICMS_GROUP_ANONYMOUS'), 'ICMS_GROUP_ANONYMOUS is not defined');
		$this->assertEquals(1, ICMS_GROUP_ADMIN, 'ICMS_GROUP_ADMIN is not 1');
		$this->assertEquals(2, ICMS_GROUP_USERS, 'ICMS_GROUP_USERS is not 2');
		$this->assertEquals(3, ICMS_GROUP_ANONYMOUS, 'ICMS_GROUP_ANONYMOUS is not 3');
	}

	/**
	 * Test URL constant
	 */
	public function testUrlConstant() {
		$this->assertTrue(defined('ICMS_URL'), 'ICMS_URL is not defined');
		$this->assertSame(getenv('URL'), ICMS_URL, 'ICMS_URL is not same as URL env variable');
	}

	/**
	 * @inheritDoc
	 */
	protected function setUp() {
		global $xoopsOption;

		$xoopsOption['nocommon'] = true;
		require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'mainfile.php';

		parent::setUp();
	}
}
