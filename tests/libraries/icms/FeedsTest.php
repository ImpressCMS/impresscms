<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class FeedsTest extends \PHPUnit_Framework_TestCase {

    /**
     * Test if is available
     */
    public function testAvailability() {
        $this->assertTrue(class_exists('icms_feeds_Rss', true), "icms_feeds_Rss class doesn't exist");
        $this->assertTrue(class_exists('icms_feeds_Simplerss', true), "icms_feeds_Simplerss class doesn't exist");
    }

    /**
     * Test RSS vars
     */
    public function testIfVarExistsForRSS() {
        $instance = new \icms_feeds_Rss();
        foreach ([
            'title',
            'url',
            'description',
            'pubDate',
            'copyright',
            'lastbuild',
            'ttl',
        ] as $var) {
            $this->assertTrue(property_exists($instance, $var), "icms_feeds_Rss doesn't have $" . $var);
        }
    }

    /**
     * Test if some public methods for RSS exists
     */
    public function testMethodsAvailabiliryRSS() {
        $instance = new \icms_feeds_Rss();
        $this->assertTrue(method_exists($instance, 'render'), 'render doesn\'t exists');
    }

    /**
     * Tests SimpleRSS methods
     */
    public function testMethodsAvailabilitySimpleRSS() {
        $instance = new \icms_feeds_Simplerss();
        $this->assertInstanceOf(\SimplePie::class, $instance, 'icms_feeds_Simplerss is not extended from SimplePie');
    }

}