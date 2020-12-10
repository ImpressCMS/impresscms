<?php

namespace ImpressCMS\Tests\Libraries\ICMS;
use icms_core_Security;
use ImpressCMS\Core\Providers\SecurityServiceProvider;
use League\Container\Container;
use PHPUnit\Framework\TestCase;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class SecurityTest extends TestCase {

    /**
     * Test if icms_core_DataFilter is available
     */
    public function testAvailability() {
        $this->assertTrue(class_exists('icms_core_Security', true), "icms_core_Security class doesn't exist");
    }

    /**
     * Tests service method
     */
    public function testService() {
		$container = new Container();
		$container->addServiceProvider(SecurityServiceProvider::class);
		$instance = $container->get('security');
		$this->assertInstanceOf(icms_core_Security::class, $instance, 'service method doesn\'t return instanceod icms_core_Security type');
    }

    /**
     * Checks if all required methods are available
     */
    public function testMethodsAvailability() {
		$container = new Container();
		$container->addServiceProvider(SecurityServiceProvider::class);
		$instance = $container->get('security');
        foreach ([ 'check', 'createToken', 'validateToken', 'clearTokens', 'filterToken', 'garbageCollection', 'checkReferer', 'checkBadips', 'getTokenHTML', 'setErrors', 'getErrors' ] as $method) {
            $this->assertTrue(method_exists($instance, $method), $method . ' doesn\'t exists');
        }
    }

    /**
     * Test how tokens is working
     */
    public function testToken() {
		$container = new Container();
		$container->addServiceProvider(SecurityServiceProvider::class);
		$instance = $container->get('security');
        $new_token = $instance->createToken();
        $this->assertIsString( $new_token, 'Generated token is not type of string');
        $this->assertTrue($instance->validateToken($new_token), 'Can\'t validate correct token');
    }
}