<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

use PHPUnit\Framework\TestCase;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class FilesTest extends TestCase {

    /**
     * Test if is available
     */
    public function testAvailability() {
        foreach ([
                'icms_file_DownloadHandler' => null,
                'icms_file_MediaUploadHandler' => null,
                'icms_file_TarDownloader' => 'icms_file_DownloadHandler',
                'icms_file_TarFileHandler' => null,
                'icms_file_ZipDownloader' => 'icms_file_DownloadHandler',
                'icms_file_ZipFileHandler' => null
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
            ],
            'icms_file_TarFileHandler' => [
                'openTAR',
                'appendTar',
                'getFile',
                'getDirectory',
                'containsFile',
                'containsDirectory',
                'addDirectory',
                'addFile',
                'removeFile',
                'removeDirectory',
                'saveTar',
                'toTar',
                'toTarOutput'
            ],
            'icms_file_ZipFileHandler' => [
                'addFile',
                'file'
            ]
        ] as $class => $methods) {
            foreach ($methods as $method) {
                $this->assertTrue(method_exists($class, $method), 'Static method ' . $method . ' doesn\'t exists for class ' . $class);
            }
        }
    }

    /**
     * Tests variables availability and types
     */
    public function testVariables() {
        foreach ([
            'icms_file_TarFileHandler' => [
                'numFiles' => 'null', // int
                'files' => 'null' // array
            ]
        ] as $class => $variables) {
            $instance = $this->getClassInstance($class);
            foreach ($variables as $variable => $type) {
                $this->assertInternalType($type, $instance->$variable, '$' . $variable . ' is not of type ' . $type . ' in instance of ' . $class);
            }
        }
    }

}