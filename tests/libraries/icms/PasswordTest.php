<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

use icms_core_Password;
use PHPUnit\Framework\TestCase;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class PasswordTest extends TestCase {

	/**
	 * Tests availability
	 */
	public function testAvailability() {
		$this->assertTrue(class_exists('icms_core_Password', true), "icms_core_Password class doesn't exist");
	}

	/**
	 * Tests if correct static methods are available
	 */
	public function testStaticMethodsAvailability() {
		 foreach ([ 'getInstance', 'createSalt' ] as $method) {
			 $this->assertTrue(method_exists('icms_core_Password', $method), $method . ' doesn\'t exists for icms_core_Password');
		 }
	}

	/**
	 * Tests if getInstance returns correct result
	 */
	public function testReturnCorrectResult() {
		$mock = icms_core_Password::getInstance();
		$this->assertInstanceOf(icms_core_Password::class, $mock, 'getInstance static method returns not \icms_core_Password instance');
	}

	/**
	 * Checks if all required methods are available
	 */
	public function testMethodsAvailability() {
		 $mock = icms_core_Password::getInstance();
		 foreach ([ 'createCryptoKey', 'passExpired', 'getUserSalt', 'getUserEncType', 'encryptPass', 'verifyPass' ] as $method) {
			 $this->assertTrue(method_exists($mock, $method), $method . ' doesn\'t exists for icms_core_Password');
		 }
	}

	/**
	 * Tests password encode and decode
	 */
	public function testPasswordEncodeAndDecode() {
		$mock = icms_core_Password::getInstance();
		$pass = md5(sha1(time()));
		$epass = $mock->encryptPass($pass);
		$this->assertNotSame($epass, $pass, 'Ecrypted and decrypted password must be not same');
	}

}