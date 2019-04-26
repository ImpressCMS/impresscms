<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class FilesTest extends \PHPUnit_Framework_TestCase {

    /**
     * Test if is available
     */
    public function testAvailability() {
        foreach ([
                'icms_file_DownloadHandler' => null,
                'icms_file_MediaUploadHandler' => null,
                'icms_file_TarDownloader' => 'icms_file_DownloadHandler',
                'icms_file_ZipDownloader' => 'icms_file_DownloadHandler'
            ] as $class => $must_be_instance_of) {
                $this->assertTrue(class_exists($class, true), $class . " class doesn't exist");
            if ($must_be_instance_of !== null) {
                $instance = $this->getClassInstance($class);
                $this->assertTrue( $instance instanceof $must_be_instance_of, $class . " is not instanceof " . $must_be_instance_of);
            }
        }
    }

    /**
     * Gets instance of class from classname
     *
     * @param string $class     ClassName
     *
     * @return object
     */
    private function getClassInstance($class) {
        $reflection = new \ReflectionClass($class);
        if ($reflection->isAbstract()) {
            $instance = $this->getMockForAbstractClass($class);
        } else {
            $instance = $this->getMockBuilder($class)
                    ->disableOriginalConstructor()
                    ->getMock();
        }
        return $instance;
    }

    /**
     * Test methods availability
     */
    public function testMethodsAvailability() {
        foreach ([
            'icms_file_DownloadHandler' => [
                'addFile',
                'addBinaryFile',
                'addFileData',
                'addBinaryFileData',
                'download'
            ],
            'icms_file_MediaUploadHandler' => [
                'fetchFromURL',
                'fetchMedia',
                'getUploadErrorText',
                'setTargetFileName',
                'getMediaName',
                'getMediaType',
                'getMediaSize',
                'getMediaTmpName',
                'getSavedFileName',
                'getSavedDestination',
                'upload',
                'checkMaxFileSize',
                'checkMaxWidth',
                'checkMaxHeight',
                'checkMimeType',
                'checkImageType',
                'sanitizeMultipleExtensions',
                'setErrors',
                'getErrors'
            ]
        ] as $class => $methods) {
            foreach ($methods as $method) {
                $this->assertTrue(method_exists($class, $method), 'Static method ' . $method . ' doesn\'t exists for class ' . $class);
            }
        }
    }

}
