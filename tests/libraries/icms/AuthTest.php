<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class AuthTest extends \PHPUnit_Framework_TestCase {

   /**
    * Test availability of needed classes
    */
   public function testAvailability() {
       $this->assertTrue(class_exists('icms_auth_Object', true), 'icms_auth_Object does\'t exist');
       $this->assertTrue(class_exists('icms_auth_Factory', true), 'icms_auth_Factory does\'t exist');
   }

   /**
    * Test auth object methods
    */
   public function testAuthObjectMethods() {
       $mock = $this->getMockForAbstractClass('icms_auth_Object');
       foreach (['authenticate', 'setErrors', 'getErrors'] as $method) {
           $this->assertTrue(method_exists($mock, $method), 'No public ' . $method . ' method');
       }
   }

   /**
    * Test factory methods
    */
   public function testFactoryMethods() {
       $this->assertTrue(method_exists('icms_auth_Factory', 'getAuthConnection'), 'No public static getAuthConnection method');
   }

   /**
    * Test Ads auth provider
    */
   public function testAdsAuth() {
       $this->doDefaultAuthTest('ads');
   }

   /**
    * Test LDAP auth provider
    */
   public function testLDAPAuth() {
       $this->doDefaultAuthTest('ldap');
   }

   /**
    * Test local auth provider
    */
   public function testLocalAuth() {
       $this->doDefaultAuthTest('local');
   }

   /**
    * Test openid auth provider
    */
   public function testOpenIDAuth() {
       $this->doDefaultAuthTest('openid');
   }

   /**
    * Do default auth provider test
    *
    * @param string $name
    */
   private function doDefaultAuthTest($name) {
       $provider = \icms_auth_Factory::getAuthConnection($name);
       $this->assertInternalType('object', $provider, "$name auth method doesn't exists");
       $this->assertTrue($provider instanceof \icms_auth_Object, 'Auth method $name is not correct');
   }

}