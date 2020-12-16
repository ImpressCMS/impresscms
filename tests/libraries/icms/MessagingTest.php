<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

use PHPMailer\PHPMailer\PHPMailer;
use PHPUnit\Framework\TestCase;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class MessagingTest extends TestCase {

    /**
     * Test if is available
     */
    public function testAvailability() {
        foreach ([
            'icms_messaging_EmailHandler' => PHPMailer::class,
            'icms_messaging_Handler' => null
        ] as $class => $must_be_instances_of) {
            $this->assertTrue(class_exists($class, true), $class . ' does\'t exist');
            if ($must_be_instances_of === null) {
                continue;
            }
            $instance = $this->getClassInstance($class);
            foreach ($must_be_instances_of as $must_be_instance_of) {
                $this->assertInstanceOf($must_be_instance_of, $instance, $class . ' must be instance of ' . $must_be_instance_of . ' but is not');
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
        $instance = $this->getMockBuilder($class)
                    ->disableOriginalConstructor()
                    ->getMock();
        return $instance;
    }

    /**
     * Test methods availability
     */
    public function testMethodsAvailability() {
        foreach ([
            'icms_messaging_Handler' => [
                'reset',
                'setTemplateDir',
                'setTemplate',
                'setFromEmail',
                'setFromName',
                'setFromUser',
                'setPriority',
                'setSubject',
                'setBody',
                'useMail',
                'usePM',
                'send',
                'sendPM',
                'sendMail',
                'getErrors',
                'getSuccess',
                'assign',
                'addHeaders',
                'setToEmails',
                'setToUsers',
                'setToGroups'
            ],
            'icms_messaging_EmailHandler' => [
                'AddrFormat',
                'encodeFromName',
                'encodeSubject',
                'encodeBody'
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
            'icms_messaging_EmailHandler' => [
                'From' => 'assertIsString',
                'FromName' => 'assertIsString',
                'Mailer' => 'assertIsString',
                'Sendmail' => 'assertIsString',
                'Host' => 'assertIsString',
                'SMTPSecure' => 'assertIsString',
                'SMTPAuth' => 'assertIsBool',
                'Username' => 'assertIsString',
                'Password' => 'assertIsString',
                'Port' => 'assertIsInt'
            ]
        ] as $class => $variables) {
            $instance = $this->getClassInstance($class);
            foreach ($variables as $variable => $func) {
                $this->$func($instance->$variable, '$' . $variable . ' is not of correct type ');
            }
        }
    }

}