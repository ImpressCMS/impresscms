<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

use ImpressCMS\Core\IPF\AbstractDatabaseHandler;
use ImpressCMS\Core\IPF\AbstractDatabaseModel;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class ImageTest extends \PHPUnit_Framework_TestCase {

    /**
     * Test if is available
     */
    public function testAvailability() {
        foreach ([
                'icms_image_category_Handler' => AbstractDatabaseHandler::class,
                'icms_image_category_Object' => AbstractDatabaseModel::class,
                'icms_image_set_Handler' => AbstractDatabaseHandler::class,
                'icms_image_set_Object' => AbstractDatabaseModel::class,
                'icms_image_Handler' => AbstractDatabaseHandler::class,
                'icms_image_Object' => AbstractDatabaseModel::class,
                'icms_image_body_Handler' => AbstractDatabaseHandler::class,
                'icms_image_body_Object' => AbstractDatabaseModel::class
            ] as $class => $must_be_instance_of) {
                $this->assertTrue(class_exists($class, true), $class . " class doesn't exist");
            if ($must_be_instance_of !== null) {
                $instance = $this->getMockBuilder($class)
                    ->disableOriginalConstructor()
                    ->getMock();
                $this->assertInstanceOf($must_be_instance_of, $instance, $class . ' is not instanceof ' . $must_be_instance_of);
            }
        }
    }

    /**
     * Tests image body functionality
     */
    public function testImageBody() {
        $image_handler = \icms::handler('icms_image');
        $image = $image_handler->create();
        $this->assertTrue(property_exists($image, 'image_body'), 'icms_image_Object doesn\'t have image_body property');
        $test_var = sha1(microtime(true));
        $image->image_body = $test_var;
        $this->assertSame($test_var, $image->getVar('image_body'), 'getVar for icms_image_Object doesn\'t work as expected (I)');
        $image->image_body = null;
        $image->setVar('image_body', $test_var);
        $this->assertSame($test_var, $image->getVar('image_body'), 'getVar for icms_image_Object doesn\'t work as expected (II)');
    }

}