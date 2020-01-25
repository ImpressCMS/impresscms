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
	public function testIcmsMainfileIncludedConstantHandling(): void
	{
		self::assertTrue(defined('ICMS_MAINFILE_INCLUDED'), 'ICMS_MAINFILE_INCLUDED is not defined after mainfile.php inclusion');
	}

	/**
	 * Tests path constant
	 */
	public function testPathConstant(): void
	{
		self::assertTrue(defined('ICMS_ROOT_PATH'), 'ICMS_ROOT_PATH is not defined');
		self::assertSame(ICMS_ROOT_PATH, dirname(dirname(__DIR__)), 'ICMS_ROOT_PATH has bad value');
	}

	/**
	 * Tests default group constants
	 */
	public function testDefaultGroupConstants(): void
	{
		self::assertTrue(defined('ICMS_GROUP_ADMIN'), 'ICMS_GROUP_ADMIN is not defined');
		self::assertTrue(defined('ICMS_GROUP_USERS'), 'ICMS_GROUP_USERS is not defined');
		self::assertTrue(defined('ICMS_GROUP_ANONYMOUS'), 'ICMS_GROUP_ANONYMOUS is not defined');
		self::assertEquals(1, ICMS_GROUP_ADMIN, 'ICMS_GROUP_ADMIN is not 1');
		self::assertEquals(2, ICMS_GROUP_USERS, 'ICMS_GROUP_USERS is not 2');
		self::assertEquals(3, ICMS_GROUP_ANONYMOUS, 'ICMS_GROUP_ANONYMOUS is not 3');
	}

	/**
	 * Test URL constant
	 */
	public function testUrlConstant(): void
	{
		self::assertTrue(defined('ICMS_URL'), 'ICMS_URL is not defined');
		self::assertSame(env('URL'), ICMS_URL, 'ICMS_URL is not same as URL env variable');
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
